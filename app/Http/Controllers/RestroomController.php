<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Restroom;

class RestroomController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), Restroom::getValidationRules());

        /* if the restroom input given by user is invalid,
        redirect back to the 'add' page with the errors provided */
        if ($validator->fails()) {
            return redirect('/add')
                ->withInput()
                ->withErrors($validator);
        }

        /* create a new Restroom object and then save it to the database */
        $restroom = new Restroom();
        self::updateRestroomFromRequest($request, $restroom);
        $restroom->save();

        return redirect('/restroom_list');
    }

    public function edit(Request $request)
    {
      $restroom = Restroom::find($request->id);

      $validator = Validator::make($request->all(), Restroom::getValidationRules());

      if($validator->fails())
      {
        return redirect('/edit/'.$restroom->id)->withInput()->withErrors($validator->errors());
      }

      /* Get the edited values and add to database */
      self::updateRestroomFromRequest($request, $restroom);
      $restroom->update();

      return redirect('/restroom_list');

    }

    public static function updateRestroomFromRequest(Request $request, Restroom $restroom) : Restroom
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
}
