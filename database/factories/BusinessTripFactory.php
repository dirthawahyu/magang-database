<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessTrip>
 */
class BusinessTripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'note' => $this->faker->sentence(),
            'photo_document' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement([0, 1, 2, 3]),
            'company' => \App\Models\Company::factory(),
            'phone_number' => $this->faker->phoneNumber(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'extend_day' => $this->faker->randomNumber(1),
            'pic_company' => $this->faker->name(),
            'pic_role' => $this->faker->jobTitle(),
        ];
    }
}