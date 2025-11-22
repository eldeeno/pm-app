<?php

namespace App\Enums;

enum TaskStatus: string
{
    case COMPLETED = 'completed';
    case IN_PROGRESS = 'in_progress';
    case OVERDUE = 'overdue';

    case PENDING = 'pending';

    /**
     * Get the label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::COMPLETED => 'Completed',
            self::IN_PROGRESS => 'In Progress',
            self::OVERDUE => 'Overdue',
            self::PENDING => 'Pending',
        };
    }

    /**
     * Get the color for the status (for UI purposes).
     */
    public function color(): string
    {
        return match ($this) {
            self::COMPLETED => 'green',
            self::IN_PROGRESS => 'blue',
            self::OVERDUE => 'red',
            self::PENDING => 'gray',
        };
    }
}
