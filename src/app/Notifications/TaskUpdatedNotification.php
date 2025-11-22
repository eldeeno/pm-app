<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $message)
    {
        $this->task = $task;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Task Updated: ' . $this->task->name)
            ->line($this->message)
            ->line('Task Name: ' . $this->task->name)
            ->line('Description: ' . $this->task->description)
            ->line('Start Date: ' . $this->task->start_date)
            ->line('End Date: ' . $this->task->end_date);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
