<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

      DB::table('users')->insert([
    'name'  =>'Евгений Андреев',
    'email'  =>'Andreev-e@mail.ru',
    'password'  =>'$2y$10$1ed9zbgKBZCY3zXAfPUlmuK4fO3KbOqdE2u18LVlqITzceI0JvOrm',
    ]);
         \App\Models\User::factory(10)->create();

         \App\Models\Tags::factory(20)->create();
         \App\Models\Locations::factory(20)->create();
         \App\Models\Pois::factory(20)->create();
         
         for ($i=0; $i < 20; $i++) {
           DB::table('pois_locations')->insert([
             'pois_id'  =>rand(1,20),
             'locations_id'  =>rand(1,20),
                ]);

              DB::table('pois_tags')->insert([
            'pois_id'  =>rand(1,20),
            'tags_id'  =>rand(1,20),
            ]);
         }



    }
}
