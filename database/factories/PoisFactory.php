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
        $name=$this->faker->name();
        return [
          'name' => $name,
          'url' => Str::slug($name),
          'user_id' => 1,
          'status' => rand(0,1),
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
