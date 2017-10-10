<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Restroom;

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
            $newRestroom = Restroom::create([
                'name' => (isset($restroom['2']) ? $restroom['2'] : ""),
                'description' => (isset($restroom['7']) ? $restroom['7'] : ""),
                'lat' => (isset($restroom['43']) ? $restroom['43'] : ""),
                'lng' => (isset($restroom['44']) ? $restroom['44'] : ""),
                'reports' => 0
            ]);

            if ($restroom['8'] == 'True') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 1,
                    'restroom_id' => $newRestroom->id
                ]);
            }

            if ($restroom['9'] == 'True') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 2,
                    'restroom_id' => $newRestroom->id
                ]);
            }

            if ($restroom['11'] == 'True') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 4,
                    'restroom_id' =>$newRestroom->id
                ]);
            }

            if ($restroom['21'] == 'True') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 3,
                    'restroom_id' => $newRestroom->id
                ]);
            }

            if ($restroom['35'] == 'True') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 6,
                    'restroom_id' => $newRestroom->id
                ]);
            }

            if ($restroom['38'] == 'True') {
                DB::table('restroom_tag')->insert([
                    'tag_id' => 5,
                    'restroom_id' => $newRestroom->id
                ]);
            }
        }
    }
}
