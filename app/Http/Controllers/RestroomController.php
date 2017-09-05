<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
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
        $hashedFilename = hash('sha256', $hashedFilename);
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
        if (!self::exceedImageLimitInDatabase($newRestroom)) {
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
        if (!self::exceedImageLimitInDatabase($restroom)) {
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

    public function searchByGeoPos(Request $request)
    {
        /* Get latitude/longitude values in Request */
        $lat = self::simplifyLatLngVal($request->lat);
        $lng = self::simplifyLatLngVal($request->lng);

        /* Don't do anything if lat/lng are not provided */
        if (is_null($lat) || is_null($lng)) {
            return "ERR_LAT_OR_LNG_NOT_GIVEN";
        }

        /* How big the area is around the center position to find results for */
        /* 0.01 is about a 15 minute walk */
        $radius = 0.01;

        /* Calculate bounds, for example:
            $lat = 15
            $lng = 12
            $radius = 4

            $b1Lat = 15 + 4 = 11;
            $b1Lng = 12 - 4 = 8;
            $b2Lat = 15 - 4 = 19;
            $b2Lng = 12 + 4 = 16;
        */
        $b1Lat = $lat + $radius;
        $b1Lng = $lng - $radius;
        $b2Lat = $lat - $radius;
        $b2Lng = $lng + $radius;

        /* Array to store restrooms found within the radius */
        $results = [];

        /* Check each restroom and see if they fall in bounds */
        foreach (Restroom::all() as $r) {
            $currLat = self::simplifyLatLngVal($r->lat);
            $currLng = self::simplifyLatLngVal($r->lng);

            /* If Restroom is in range, add it to array of found restrooms */
            if ($currLat <= $b1Lat && $currLat >= $b2Lat && $currLng >= $b1Lng && $currLng <= $b2Lng) {
                $results[] = $r;
            }
        }

        /* Convert results array into Eloquent Collection object to easily
        encode to a JSON String on the next line when returning */
        $resultsCollection = new Collection($results);

        /* Return the results as a JSON String */
        return $resultsCollection->toJson();
    }

    private function simplifyLatLngVal(string $latLngText) : float {
        return round(floatval($latLngText), 5);
    }
}
