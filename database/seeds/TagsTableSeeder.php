<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = array(
            array(
                'name' => 'Male',
                'description' => 'This restroom allows male access.',
                'iconPath' => 'img/male.png'
            ),
            array(
                'name' => 'Female',
                'description' => 'This restroom allows female access.',
                'iconPath' => 'img/female.png'
            ),
            array(
                'name' => 'Disabled',
                'description' => 'This restroom accomodates people with physical disabilities.',
                'iconPath' => 'img/disabled.png'
            ),
            array(
                'name' => 'Unisex',
                'description' => 'This restroom has unisex facilities.',
                'iconPath' => 'img/unisex.png'
            ),
            array(
                'name' => 'Syringe',
                'description' => 'This restroom has syringe disposal units.',
                'iconPath' => 'img/syringe.png'
            ),
            array(
                'name' => 'Baby',
                'description' => 'This restroom has baby changerooms.',
                'iconPath' => 'img/baby.png'
            )
        );

        foreach ($tags as $tag) {
            DB::table('tags')->insert([
                'name' => $tag['name'],
                'description' => $tag['description'],
                'iconPath' => $tag['iconPath']
            ]);
        }
    }
}
