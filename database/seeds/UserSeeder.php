<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  // use for when there is no model available

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(App\User::class, 10)->create()
      ->each(function ($user){
        // for each user assign 1-8 hobbies
        factory(App\Hobby::class, rand(1, 3) )->create(
          [
            'user_id' => $user->id  // assign user id (creator) to each hobby
          ]
        )
        ->each(function ($hobby){
          $tag_ids = range(1, 8);  // create array from 1-8 (number of tags)
          shuffle($tag_ids);
          $assignments = array_slice($tag_ids, 0, rand(0, 8)); // pick 0 to 8 tages; example: 5, 2, 8
          foreach($assignments as $tag_id) {
            DB::table('hobby_tag')   // use DB Facade for when there is no model available like hobby_tag
              ->insert(
                [
                  'hobby_id' => $hobby->id,
                  'tag_id' => $tag_id,
                  'created_at' => Now(),
                  'updated_at' => Now()

                ]
              );
          }
      });
    });
  }
}