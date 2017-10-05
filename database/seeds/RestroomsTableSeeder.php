<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RestroomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restroomList = array_map('str_getcsv', file(storage_path('victorian-public-toilets.csv')));
        /*var_dump($restroomList);*/

        foreach ($restroomList as $restroom) {
            DB::table('restrooms')->insert([
                'name' => $restroom['2'],
                'description' => $restroom['7'],
                'lat' => $restroom['43'],
                'lng' => $restroom['44'],
                'reports' => 0
            ]);
        }
        /*
           DB::table('restroom_tag')->insert([
            '0' => 'restroom_id'
            (Tags?)
        ]) */
    }
}
