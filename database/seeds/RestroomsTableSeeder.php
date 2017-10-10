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
                'description' => (isset($restroom['7']) ? $restroom['7'] : ""),
                'lat' => (isset($restroom['43']) ? $restroom['43'] : ""),
                'lng' => (isset($restroom['44']) ? $restroom['44'] : ""),
                'reports' => 0
            ]);

            if ($restroom ['8'] == 'TRUE') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 0,
                    'restroom_id' => 2
                ]);
            }

            if ($restroom ['9'] == 'TRUE') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 1,
                    'restroom_id' => 2
                ]);
            }

            if ($restroom ['11'] == 'TRUE') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 3,
                    'restroom_id' => 2
                ]);
            }

            if ($restroom ['21'] == 'TRUE') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 2,
                    'restroom_id' => 2
                ]);
            }

            if ($restroom ['35'] == 'TRUE') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 5,
                    'restroom_id' => 2
                ]);
            }

            if ($restroom ['38'] == 'TRUE') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 4,
                    'restroom_id' => 2
                ]);
            }
        }
    }
}
