<?php

namespace Database\Factories;

use App\Models\Locations;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LocationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Locations::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

      $name=$this->faker->name();
        return [
          'name' => $name,
          'url' => Str::slug($name),
          'parent' =>rand(0,50),
          'type' =>rand(0,3),
        ];
    }
}
