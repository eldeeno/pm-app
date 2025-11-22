<?php

namespace Database\Factories;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph(),
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(10),
            'project_id' => Project::inRandomOrder()->first()->id ?? Project::factory()
        ];
    }
}
