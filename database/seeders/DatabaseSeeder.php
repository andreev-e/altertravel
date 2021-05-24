<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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

/*
      DB::table('users')->insert([
    'name'  =>'Евгений Андреев',
    'email'  =>'Andreev-e@mail.ru',
    'login'  =>'andreev',
    'password'  =>'$2y$10$xu9WrcqnZzhp.2FYULXti.MYCR8Kw/fkjDLV5JdXYDO.bgnJfmCBy',
    ]);
*/
    $categories=array('Архитектура','Природа','История/Культура','Техноген','Музей','Памятник','Ночлег','Еда','Покупки','Развлечения');
    foreach ($categories as $category) {
      DB::table('categories')->insert([
    'name'  =>$category,
    'url'  =>Str::slug($category),
    ]);
    }

         //\App\Models\Tags::factory(5)->create();

    }
}
