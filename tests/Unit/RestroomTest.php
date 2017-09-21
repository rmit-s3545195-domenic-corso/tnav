<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Restroom;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RestroomTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testValidationRules()
    {
        $validator = Validator::make([
          'rr_name' => 'RMIT Building 80',
          'rr_desc' => 'Cleaner than other toilets in RMIT',
          'rr_lat' => '37.002',
          'rr_lng' => '-144.020492',
          'rr_floor' => '10',
          'rr_added_by' => 'Lu'
        ], Restroom::getValidationRules());

        $this->assertTrue($validator->passes());
    }


}
