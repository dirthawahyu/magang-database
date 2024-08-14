<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'nik' => $this->faker->randomNumber(),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'religion' => $this->faker->randomElement(['Islam', 'Kristen', 'Hindu', 'Budha']),
            'birth_date' => $this->faker->date(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'gender' => $this->faker->randomElement([0, 1, 2]),
            'profile_photo' => $this->faker->imageUrl(),
            'ktp_photo' => $this->faker->imageUrl(),
            'no_rekening' => $this->faker->bankAccountNumber(),
            'blacklist_reason' => $this->faker->sentence(),
            'block_date' => $this->faker->randomNumber(),
            'marital_status' => $this->faker->randomElement([0, 1, 2]),
        ];
    }
}