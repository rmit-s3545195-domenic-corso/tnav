<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Review;

class ReviewController extends Controller
{
    public function add(Request $request) {
        $validator = Validator::make($request->all(), Review::getValidationRules());
        
        /* If the parameters are not valid, return a JSON string of errors. */
        if ($validator->fails()) {
            return $validator->errors();
        }
        
        /* Add values to model. */
        $newReview = new Review($request->all());
        $newReview->reports = 0;
        
        /* Save review to database. */
        $newReview->save();
        
        return 'SUCCESS';
    }
    
    public function report(Review $review) {
        $review->reports++;
        $review->save();
    }
}
