<?php

use Illuminate\Database\Seeder;

use App\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create an array of key value pairs
        $tags = [
            'Sports' => 'primary', // blue
            'Relaxation' => 'secondary', // grey
            'Fun' => 'warning', // yellow
            'Nature' => 'success', // green
            'Inspiration' => 'light', // white grey
            'Friends' => 'info', // turquoise
            'Love' => 'danger', // red
            'Interest' => 'dark' // black-white
        ];

        foreach ($tags as $key => $value) { // create as many tags as key value pairs
            $tag = new Tag(
                [
                    'name' => $key, // assign
                    'style' => $value   // assign
                ]
            );
            $tag->save();
        }

    }
}
