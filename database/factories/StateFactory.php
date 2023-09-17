<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\City;
use App\Models\State;
class StateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
     protected $model = State::class;
    public function definition()
    {
        return [
           'name'=>ucwords($this->faker->name()),
           
        ];
    }
}
