<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlanningRealizationHeader>
 */
class PlanningRealizationHeaderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_business_trip' => \App\Models\Businesstrip::factory(),
            'id_pengeluaran_kategori' => \App\Models\CategoryExpenditure::factory(),
            'keterangan' => $this->faker->sentence(),
            'nominal_planning' => $this->faker->randomNumber(5),
            'nominal_realization' => $this->faker->randomNumber(5),
        ];
    }
}