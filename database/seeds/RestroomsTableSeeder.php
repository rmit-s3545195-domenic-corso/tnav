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


        foreach ($restroomList as $restroom) {
            if (count($restroom) != 45) { continue; }
            DB::table('restrooms')->insert([
                'name' => (isset($restroom['2']) ? $restroom['2'] : ""),
                'description' => (isset($restroom['7']) ? $restroom['7'] : "Dikhead"),
                'lat' => (isset($restroom['43']) ? $restroom['43'] : ""),
                'lng' => (isset($restroom['44']) ? $restroom['44'] : ""),
                'reports' => 0
            ]);
        }
    }
}
