<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlanningRealizationLine>
 */
class PlanningRealizationLineFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\PlanningRealizationLine::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_planning_realization' => \App\Models\PlanningRealizationHeader::factory(),
            'photo_proof' => $this->faker->imageUrl(),
            'keterangan' => $this->faker->sentence(),
        ];
    }
}