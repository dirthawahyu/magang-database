<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BusinessTrp;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripDetail>
 */
class TripDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_user' => \App\Models\User::factory(),
            'id_business_trip' => \App\Models\BusinessTrip::factory(),
        ];
    }
}