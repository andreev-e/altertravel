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
    'login'  =>'andreev',
    'password'  =>'$2y$10$1ed9zbgKBZCY3zXAfPUlmuK4fO3KbOqdE2u18LVlqITzceI0JvOrm',
    ]);

         \App\Models\Tags::factory(5)->create();
         \App\Models\Pois::factory(200)->create();

    }
}
