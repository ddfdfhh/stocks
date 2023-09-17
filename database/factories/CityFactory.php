<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\City;
use App\Models\State;
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
     protected $model = City::class;
    public function definition()
    {
        return [
           'name'=>$this->faker->name(),
           'state_id'=>rand(1,State::count()),
           'status'=>'Active'
        ];
    }
}
