<?php

namespace App;

enum UserStatus: int
{
    case OFFLINE = 0;
    case ONLINE = 1;

    /**
     * Get the status label.
     */
    public function label(): string
    {
        return match ($this) {
            self::ONLINE => 'Online',
            self::OFFLINE => 'Offline',
        };
    }

    /**
     * Get the CSS classes for the status indicator.
     */
    public function indicatorClasses(): string
    {
        return match ($this) {
            self::ONLINE => 'bg-green-500',
            self::OFFLINE => 'bg-slate-400',
        };
    }

    /**
     * Get the status color for UI elements.
     */
    public function color(): string
    {
        return match ($this) {
            self::ONLINE => 'green',
            self::OFFLINE => 'gray',
        };
    }
}
