<?php

use App\Models\Task;
use App\Notifications\TaskNearingDeadlineNotification;
use App\Notifications\TaskOverdueNotification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule: Check for tasks nearing their deadline (e.g., within 24 hours)
Schedule::call(function () {
    $tasks = Task::where('end_date', '>', now())
        ->where('end_date', '<=', now()->addDay())
        ->where('status', '!=', 'completed')
        ->get();

    foreach ($tasks as $task) {
        if ($task->assignee) {
            $task->assignee->notify(new TaskNearingDeadlineNotification($task));
        }
    }
})->everyMinute();

// Schedule: Check for overdue tasks
Schedule::call(function () {
    $tasks = Task::where('end_date', '<', now())
        ->where('status', '!=', 'completed')
        ->get();

    foreach ($tasks as $task) {
        if ($task->assignee) {
            $task->assignee->notify(new TaskOverdueNotification($task));
        }
    }
})->everyMinute();
