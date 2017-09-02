<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Validator;
use App\Restroom;
use App\RestroomPhoto;
use Storage;
use Session;

class RestroomController extends Controller
{
    const FILETYPES = array('image/png', 'image/jpeg', 'image/jpg');
    const HASH_RUNS = 20000;
    const SALT_PATH = "/app/filename_salt";

    /* Update Restroom attributes, accepts Request and Restroom */
    private static function assignRestroomAttributesFromRequest(Request $request, Restroom $restroom) : Restroom
    {
        $restroom->name = $request->rr_name;
        $restroom->description = $request->rr_desc;
        $restroom->lat = $request->rr_lat;
        $restroom->lng = $request->rr_lng;
        $restroom->floor = $request->rr_floor;
        $restroom->addedBy = $request->rr_added_by;
        $restroom->reports = 0;

        return $restroom;
    }

    private static function hashFileName(string $filename) : string
    {
        $hashedFilename = $filename;
        $salt = file_get_contents(storage_path(self::SALT_PATH));

        for ($i = 0; $i < self::HASH_RUNS; $i++) {
            $hashedFilename = hash('sha256', $hashedFilename.$salt);
        }

        return $hashedFilename;
    }

    /* Update Restroom Photo attributes, accepts Request and Restroom */
    private static function assignRestroomPhotoAttributesFromRequest(Request $request, int $array_location, Restroom $restroom, RestroomPhoto $restroom_photo) : RestroomPhoto
    {
        /* Hash name for the image */
        $restroom_photo->name = self::hashFileName($request->rr_photos[$array_location]->getClientOriginalName());
        $restroom_photo->addedBy = $restroom->addedBy;
        $restroom_photo->reports = 0;
        $restroom_photo->path = public_path('/img/').$request->rr_photos[$array_location]->getClientOriginalName();
        $restroom_photo->restroomID = $restroom->id;
        return $restroom_photo;
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), Restroom::getValidationRules());

        /* If Restroom input is not valid, redirect back to the 'add-restroom'
        page and display the errors */
        if ($validator->fails()) {
            return redirect('/add-restroom')
                ->withInput()
                ->withErrors($validator);
        }

        if(count($request->rr_photos) > 3) {
            Session::flash("flash_filecount", "You may only upload 3 images");
            return redirect('/add-restroom')
                ->withInput();
        }

        /* Create a new Restroom */
        $newRestroom = new Restroom();

        /* Assign its attributes from the request */
        self::assignRestroomAttributesFromRequest($request, $newRestroom);

        $newRestroom->save();

        $path = public_path('/img/'.$newRestroom->id);
        if (!file_exists($path)) {
            File::makeDirectory(public_path('/img/'.$newRestroom->id));
        }
        if (self::exceedImageLimitInDirectory(count($request->rr_photos), $path, $newRestroom)) {
            Session::flash("flash_filecount", "Photo Limit for this restroom have been reached");
            return redirect('/add-restroom')
                ->withInput();
        }

        /* Upload the file to the public image path */
        self::upload($path, $request, $newRestroom);

        /* Check if the number is less than 15 */
        if (!self::exceedImageLimitInDirectory($newRestroom)) {
            for ($i = 0; $i < count($request->rr_photos); $i++) {
                if (self::file_exists_in_database($i, $request)) { continue; } else {
                    /* Check if the image is already in the database */

                    /* Create a new Restroom Photo if there are images in the request */
                    $newRestroomPhoto = new RestroomPhoto();

                    /* Assign its attributes from the request */
                    self::assignRestroomPhotoAttributesFromRequest($request, $i, $newRestroom, $newRestroomPhoto);

                    $newRestroomPhoto->save();
                }
            }
        }

        /* Redirect to restroom list for development, change this later on */
        return redirect('/restroom-list');
    }

    public function edit(Request $request)
    {
        /* Resolves a Restroom from the database given an ID */
        $restroom = Restroom::find($request->id);

        $validator = Validator::make($request->all(), Restroom::getValidationRules());

        /* If Restroom input is not valid, redirect back to the 'edit'
        page and display the errors */
        if ($validator->fails()) {
            return redirect('/edit/' . $restroom->id)
                ->withInput()
                ->withErrors($validator);
        }

        if(count($request->rr_photos) > 3) {
            Session::flash("flash_filecount", "You may only upload 3 images at one time");
            return redirect('/edit/' . $restroom->id)
                ->withInput();
        }

        /* Update Restroom attributes */
        self::assignRestroomAttributesFromRequest($request, $restroom);

        $path = public_path('/img/'.$restroom->id);
        if (!file_exists($path)) {
            File::makeDirectory(public_path('/img/'.$restroom->id));
        }

        if (self::exceedImageLimitInDirectory(count($request->rr_photos), $path, $restroom)) {
            Session::flash("flash_filecount", "Photo Limit for this restroom have been reached");
            return redirect('/edit/' . $restroom->id)
                ->withInput();
        }

        /* Upload the file to the public image path */
        self::upload($path, $request, $restroom);

        /* Check if the number is less than 15 */
        if (!self::exceedImageLimitInDirectory($restroom)) {
            for ($i = 0; $i < count($request->rr_photos); $i++) {
                if (self::file_exists_in_database($i, $request)) { continue; } else {
                    /* Create a new Restroom Photo if there are images in the request */
                    $newRestroomPhoto = new RestroomPhoto();

                    /* Assign its attributes from the request */
                    self::assignRestroomPhotoAttributesFromRequest($request, $i, $restroom, $newRestroomPhoto);

                    $newRestroomPhoto->save();
                }
            }
        }

        $restroom->update();


        return redirect('/restroom-list');
    }

    public function delete(Request $request)
    {
        RestroomPhoto::where('restroomID', '=', $request->id)->delete();
        File::deleteDirectory(public_path('/img/'.$request->id));
        Restroom::find($request->id)->delete();
        Session::flash("flash_success", "Restroom has been deleted");
        return redirect('/');
    }

    public function search(Request $request)
    {
        $queryText = $request->q;

        /* If query is empty, return an empty JSON object  */
        if (!trim($queryText)) {
            return "[]";
        }

        /* Query database and return JSON formatted results */
        return Restroom::where('description', 'like', '%'.$queryText.'%')
            ->orWhere('name', 'like', '%'.$queryText.'%')
            ->get()
            ->toJson();
    }

    /* Passes in the request with the uploaded files and stores them in a folder with the restroom id */
    private function upload(String $path, Request $request, Restroom $curr_restroom)
    {
        if ($request->rr_photos != null) {
            $rr_photos = $request->rr_photos;
            foreach($rr_photos as $photo) {
                if (file_exists($path.'/'.$photo->getClientOriginalName())) {
                    continue;
                } else { $photo->move($path, $photo->getClientOriginalName()); }
            }
        }
    }

    private function file_exists_in_database(int $array_location, Request $request) : bool
    {
        if (RestroomPhoto::where('path', '=', public_path('/img/').$request->rr_photos[$array_location]->getClientOriginalName()) != null) {
            return true;
        } else { return false; }
    }

    private function exceedImageLimitInDatabase(Restroom $restroom) : bool
    {
        $restroomPhotos = RestroomPhoto::where('restroomID', '=', $restroom->id)->get();
        if (count($restroomPhotos) > 15) {
            return true;
        } else { return false; }
    }

    private function exceedImageLimitInDirectory(int $uploadedFiles, String $path, Restroom $curr_restroom) : bool
    {
        $files = (count(scandir($path)) - 2);
        if ($files + $uploadedFiles > 15) {
            return true;
        } else { return false; }
    }
}
