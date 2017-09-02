<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Restroom;

class RestroomController extends Controller
{
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

        /* If Restroom input is not valid, redirect back to the 'add'
        page and display the errors */
        if ($validator->fails()) {
            return redirect('/add')
                ->withInput()
                ->withErrors($validator);
        }

        /* Create a new Restroom */
        $newRestroom = new Restroom();

        /* Assign its attributes from the request */
        self::assignRestroomAttributesFromRequest($request, $newRestroom);

        $newRestroom->save();

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

        /* Update Restroom attributes */
        self::assignRestroomAttributesFromRequest($request, $restroom);

        $restroom->update();

        return redirect('/restroom-list');
    }

    public function delete(Request $request)
    {
       Restroom::find($request->id)->delete();
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
