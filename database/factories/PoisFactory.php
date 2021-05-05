<?php

namespace Database\Factories;

use App\Models\Pois;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class poisFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pois::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories= array('Архитектура','Природа','Природа','История/Культура','Техноген','Музей','Памятник','Ночлег','Еда','Покупки','Развлечения');
        $name=$this->faker->name();
        return [
          'name' => $name,
          'url' => Str::slug($name),
          'user_id' => 1,
          'status' => 1,
          'lat' => rand(-60,60).".".rand(0,10000),
          'lng' => rand(-90,90).".".rand(0,10000),
          'category' => $categories[array_rand($categories)],
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
