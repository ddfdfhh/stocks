<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\City;
use App\Models\State;


class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
             'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->sentence(),
            'mobile_no' => $this->faker->e164PhoneNumber(), // password
            'state_id' =>rand(1,State::count()),
            'city_id' =>rand(1,City::count()),
            'gst_number'=>$this->faker->word(),
            'pan_number'=>$this->faker->word(),
            'adhaar_no'=>$this->faker->numerify('##########')
        ];
    }
}
