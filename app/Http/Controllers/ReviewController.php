<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Review;

class ReviewController extends Controller
{
    const REPORTED_REVIEWS_KEY = 'reported_reviews';

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
    
    /* 
        A list of reported reviews will be stored in the session as an array of 
        keys, for example:
            Session->reported_reviews = array('1', '4', '9')
        This means the current session holder has reported reviews with the IDs 
        of 1, 4 and 9.

        Helpful doc: https://laravel.com/docs/5.5/session
    */
    public function report(Review $review, Request $request) {
        $session = $request->session();

        /* If there is no 'reported reviews' key in the session, then add one
        before proceeding. */
        if (!$session->exists(self::REPORTED_REVIEWS_KEY)) {
            $session->put(self::REPORTED_REVIEWS_KEY, array());
        }

        $reportedReviewIds = $session->get(self::REPORTED_REVIEWS_KEY);

        /* If the guest has already reported this review, return an error 
        message saying so. */
        if (in_array($review->id, $reportedReviewIds)) {
            return 'ALREADY_REPORTED';
        }

        /* If they have not already reported this review, increment the amount
        of reports the review has, save it and add it to the list of reviews
        the guest has reported. */

        $review->reports++;
        $review->save();
        $session->push(self::REPORTED_REVIEWS_KEY, $review->id);

        return 'SUCCESS';
    }
}
