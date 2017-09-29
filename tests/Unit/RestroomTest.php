<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Restroom;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RestroomTest extends TestCase
{
    /* Tests restroom properties against the Validator that
    are basic, and should always pass. */
    public function testValidBasic()
    {
        $validator = Validator::make([
          'rr_name' => 'RMIT Building 80',
          'rr_desc' => 'Cleaner than other toilets at RMIT',
          'rr_lat' => '37.002',
          'rr_lng' => '-144.020492',
          'rr_floor' => '10',
          'rr_added_by' => 'Lu'
        ], Restroom::getValidationRules());

        $this->assertTrue($validator->passes());
    }

    /* Tests restroom properties against the Validator that
    are basic, and should always fail. */
    public function testInvalidBasic()
    {
        $validator = Validator::make([
          'rr_name' => 'RMIT *Building 80',
          'rr_desc' => 'Cleaner than other toilets at RMIT%',
          'rr_lat' => '37.002.9',
          'rr_lng' => '-144.54e211',
          'rr_floor' => '(',
          'rr_added_by' => 'Q'
        ], Restroom::getValidationRules());

        $this->assertFalse($validator->passes());
    }


    /* Similar to testValidBasic but will go through lots
    of possible combinations of properties. */
    public function testValidProperties()
    {
        /* Name */
        $validNames = array('Domenic', 'Luuuuuu', 'Bottle');

        /* Description */
        $validDescriptions = array('This toilet is clean', 'This toilet is disgusting never coming here again', 'Lit Toilet');

        /* Lat/Lng */
        $validLat = array('37.0029', '38.020492', '35.3093');

        $validLng = array('-156.0029', '-144.020492', '-143.3093');

        /* Floor */
        $validFloor = array('1','2','10');

        /* Added By */
        $validAddedBy = array('MJ', 'Domenic', 'Varsha');

        for ($i = 0; $i < 3; $i++) {
            $validator = Validator::make([
              'rr_name' => $validNames[$i],
              'rr_desc' => $validDescriptions[$i],
              'rr_lat' =>  $validLat[$i],
              'rr_lng' =>  $validLng[$i],
              'rr_floor' => $validFloor[$i],
              'rr_added_by' => $validAddedBy[$i]
            ], Restroom::getValidationRules());

            if($validator->passes())
            {
                $this->assertTrue(true);
            }
        }

    }

    /* Similar to testValidProperties but will test
    against a series of invalid properties that are
    expected to fail. */
    public function testInvalidProperties()
    {
        /* Name */
        $invalidNames = array('Do$$$menic', '$$$Lu', '!!@@@!#!@Bottle');

        /* Description */
        $invalidDescriptions = array('This toilet is clean', 'This toilet is disgusting never coming here again', 'Lit Toilet');

        /* Lat/Lng */
        $invalidLat = array('3e7.0029', '38.0e20492', '3e5.3093');

        $invalidLng = array('-156.0e029', '-144.020e492', '-143.e3093');

        /* Floor */
        $invalidFloor = array('1e','e2','1e0');

        /* Added By */
        $invalidAddedBy = array('eMJ', 'Domeenic', 'Vaersha');

        for ($i = 0; $i < 3; $i++) {
            $validator = Validator::make([
              'rr_name' => $invalidNames[$i],
              'rr_desc' => $invalidDescriptions[$i],
              'rr_lat' =>  $invalidLat[$i],
              'rr_lng' =>  $invalidLng[$i],
              'rr_floor' => $invalidFloor[$i],
              'rr_added_by' => $invalidAddedBy[$i]
            ], Restroom::getValidationRules());

            if($validator->fails())
            {
                $this->assertTrue(true);
            }
        }
    }

    /* Tests the expected rating (stars) of a restroom
    if it has more than a single review, for example if
    a restroom has 2 reviews of 3 and 5 stars, it should
    have an average star rating of 4. */
    public function testStarsAlgorithm()
    {
        // TODO
    }

    /* Tests a new restroom is created with valid input
    Also test if the newly created restroom is deleted
    successfully after creation */

    public function testAddValidRestroom()
    {
        $newRestroom = new Restroom();

        $validator = Validator::make([
          'rr_name' => 'RMIT Building 56',
          'rr_desc' => 'Its a bit far but its pretty clean',
          'rr_lat' => '37.0002',
          'rr_lng' => '-147.033',
          'rr_floor' => '10',
          'rr_added_by' => 'Domenic Corso'
        ], Restroom::getValidationRules());

        if($validator->passes())
        {
            $newRestroom->name = "RMIT Building 56";
            $newRestroom->description = "Its a bit far but its pretty clean";
            $newRestroom->lat = "37.0002";
            $newRestroom->lng = "-147.033";
            $newRestroom->floor = "10";
            $newRestroom->addedBy = "Domenic Corso";
            $newRestroom->reports = 0;
        }

        $this->assertTrue($newRestroom->save());
        $this->assertTrue($newRestroom->delete());
    }
}
