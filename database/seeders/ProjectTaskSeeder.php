<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsers = User::role('Admin')->get();

        if ($adminUsers->isEmpty()) {
            $this->command->warn('No Admin users found. Please seed the users table first.');
            return;
        }

        $teamMembers = User::role('Team Member')->get();

        if ($teamMembers->isEmpty()) {
            $this->command->warn('No Team Member users found. Please seed the users table first.');
            return;
        }

        $projects = Project::factory()->count(3)->create([
            'user_id' => function () use (&$adminUsers) {
                return $adminUsers->pop()->id;
            },
        ]);

        $projects->each(function ($project) use (&$teamMembers) {
            for ($i = 0; $i < 3; $i++) {
                if ($teamMembers->isEmpty()) {
                    $teamMembers = User::role('Team Member')->get()->shuffle();
                }

                $teamMember = $teamMembers->pop();

                Task::factory()->create([
                    'project_id' => $project->id,
                    'status' => TaskStatus::PENDING,
                    'assignee_id' => $teamMember->id,
                ]);
            }
        });

        $this->command->info('Projects and tasks assigned to different Admin users and Team Members successfully.');
    }
}
