<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Validator;
use App\Restroom;
use App\RestroomPhoto;
use App\RestroomTag;
use Storage;
use Session;

class RestroomController extends Controller
{
    const FILETYPES = array('png', 'jpeg', 'jpg');

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

        /* Create a new Restroom */
        $newRestroom = new Restroom();

        /* Assign its attributes from the request */
        self::assignRestroomAttributesFromRequest($request, $newRestroom);

        if (!is_null($request->rr_photos)) {
            if (self::isCorrectFileExtension($request->rr_photos)) {
                $newRestroom->save();

                /* Make a new public images folder (/public/img/{$rr_id}) for the newly-added Restroom */
                $publicImgDir = "/img/$newRestroom->id";

                $fullPublicImgDir = public_path($publicImgDir);
                File::makeDirectory($fullPublicImgDir);

                /* Upload the file to the public image path */
                self::uploadImages($fullPublicImgDir, $request->rr_photos);

                /* Now the photos are uploaded, make a new database record entry for each photo, associating
                each record with the newly-created Restroom using the a foreign key */
                self::uploadImagesToDatabase($newRestroom, $request->rr_photos);
            } else {
                Session::flash("invalid_filetype", "ERROR: Images must be png, jpeg or jpg");
                return redirect('/add-restroom')->withInput();
            }
        } else {
            $newRestroom->save();
        }

        foreach ($request->all() as $k => $v) {
            if (!is_string($k)) { continue; }

            if (strpos($k, 'rr_tag_') !== false) {
                $tagID = str_replace("rr_tag_", "", $k);

                $pivotLink = new RestroomTag();

                $pivotLink->restroom_id = $newRestroom->id;
                $pivotLink->tag_id = $tagID;
                $pivotLink->timestamps = false;

                $pivotLink->save();
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

        /* Update Restroom attributes from the request */
        self::assignRestroomAttributesFromRequest($request, $restroom);

        if (!is_null($request->rr_photos)) {
            if (self::isCorrectFileExtension($request->rr_photos)) {
                /*Assign a new public images folder (/public/img/{$rr_id}) for the found Restroom */
                $publicImgDir = "/img/$restroom->id";

                $fullPublicImgDir = public_path($publicImgDir);

                /* Upload the file to the public image path */
                self::uploadImages($fullPublicImgDir, $request->rr_photos);

                /* Now the photos are uploaded, make a new database record entry for each photo if that photo doesnt already exist,
                associating each record with the newly-created Restroom using the a foreign key */
                self::uploadImagesToDatabase($restroom, $request->rr_photos);

                $restroom->update();
            } else {
                Session::flash("invalid_filetype", "ERROR: Images must be png, jpeg or jpg");
                return redirect('/edit-restroom')->withInput();
            }
        } else {
            $restroom->update();
        }

        return redirect('/restroom-list');
    }

    public function delete(Request $request)
    {
        RestroomPhoto::where('restroom_id', '=', $request->id)->delete();
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

    private static function uploadImagesToDatabase(Restroom $restroom, array $photos)
    {
        foreach ($photos as $p) {
            $currentRestroomImages = RestroomPhoto::where('restroom_id', '=',$restroom->id)->where('name', '=', $p->getClientOriginalName())->get();
            if($currentRestroomImages->count() != 0) {
                foreach ($currentRestroomImages as $photos) {
                    if ($photos->name != $p->getClientOriginalName()) {
                        $newRestroomPhoto = new RestroomPhoto();

                        self::assignRestroomPhotoAttributesFromRequest($p, $newRestroomPhoto, $restroom->id, $restroom->addedBy);

                        $newRestroomPhoto->save();
                    } else { continue; }
                }
            } else {
                $newRestroomPhoto = new RestroomPhoto();

                self::assignRestroomPhotoAttributesFromRequest($p, $newRestroomPhoto, $restroom->id, $restroom->addedBy);

                $newRestroomPhoto->save();
            }
        }
    }

    private static function uploadImages(String $prePath, array $photos)
    {
        foreach ($photos as $p) {
            $p->move($prePath, $p->getClientOriginalName());
        }
    }

    private static function assignRestroomPhotoAttributesFromRequest($actualPhoto, $restroomPhoto, $restroomID, $addedBy)
    {
        $restroomPhoto->name = $actualPhoto->getClientOriginalName();
        $restroomPhoto->addedBy = $addedBy || 'Anonymous';
        $restroomPhoto->reports = 0;
        $restroomPhoto->path = "img/$restroomID/".$actualPhoto->getClientOriginalName();
        $restroomPhoto->restroom_id = $restroomID;
    }

    public function isCorrectFileExtension(array $photos) : bool {
        foreach ($photos as $p) {
            $ext = pathinfo($p->getClientOriginalName(), PATHINFO_EXTENSION);
            /* For each photo check if the extension is in the array */
            if (!in_array($ext, self::FILETYPES))
            {
                return false;
            }
        }
        return true;
    }

    public function getReviews(Restroom $restroom) {
        dd($restroom);
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

        /* Add 'photoUrls' property to each restroom result */
        foreach ($resultsCollection as $r) {
            $photoUrls = array();
            $tagUrls = array();

            foreach ($r->photos as $p) {
                $photoUrls[] = $p->path;
            }

            foreach ($r->tags as $t) {
                $tagUrls[] = $t->iconPath;
            }

            $r->photoUrls = $photoUrls;
            $r->tagUrls = $tagUrls;
            $r->reviews = $r->reviews;
            $r->stars = $r->stars();
        }

        /* Return the results as a JSON String */
        return $resultsCollection->toJson();
    }

    private function simplifyLatLngVal(string $latLngText) : float {
        return round(floatval($latLngText), 5);
    }
}
