<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Position;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
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
            'id_role' => \App\Models\Role::factory(),
            'id_employee_group' => \App\Models\EmployeeGroup::factory(),
            'id_position' => \App\Models\Position::factory(),
            'id_company' => \App\Models\Company::factory(),
            'status' => $this->faker->randomElement([0, 1]),
            'id_tax_status' => $this->faker->randomElement(['A', 'B', 'C']),
            'nip' => $this->faker->randomNumber(),
        ];
    }
}