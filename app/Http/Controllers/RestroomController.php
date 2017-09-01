<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use App\Restroom;
use Storage;
use Session;

class RestroomController extends Controller
{
    const FILETYPES = array('jpg', 'png', 'jpeg');

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

    public function isCorrectFiletype(Request $request) : bool
    {
        if ($request->rr_photos == null) {
            return true;
        } else {
            foreach($request->rr_photos as $photos) {
                if (in_array(pathinfo($photos, PATHINFO_EXTENSION), self::FILETYPES)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
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

        if (!self::isCorrectFiletype($request)) {
            Session::flash("flash_invalid_filetype", "File type must be of type jpg, png, jpeg!");
            return redirect('/add-restroom');
        }

        /* Create a new Restroom */
        $newRestroom = new Restroom();

        /* Assign its attributes from the request */
        self::assignRestroomAttributesFromRequest($request, $newRestroom);
        self::upload($request);

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

    /* Passes in the request with the uploaded files and stores them in a folder with the restroom id */
    private function upload(Request $request)
    {
    }
}
