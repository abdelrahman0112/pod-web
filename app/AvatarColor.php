<?php

namespace App;

enum AvatarColor: string
{
    case INDIGO = 'bg-indigo-100 text-indigo-600';
    case PURPLE = 'bg-purple-100 text-purple-600';
    case PINK = 'bg-pink-100 text-pink-600';
    case BLUE = 'bg-blue-100 text-blue-600';
    case GREEN = 'bg-green-100 text-green-600';
    case YELLOW = 'bg-yellow-100 text-yellow-600';
    case RED = 'bg-red-100 text-red-600';
    case ORANGE = 'bg-orange-100 text-orange-600';
    case TEAL = 'bg-teal-100 text-teal-600';
    case CYAN = 'bg-cyan-100 text-cyan-600';
    case EMERALD = 'bg-emerald-100 text-emerald-600';
    case LIME = 'bg-lime-100 text-lime-600';
    case AMBER = 'bg-amber-100 text-amber-600';
    case ROSE = 'bg-rose-100 text-rose-600';
    case VIOLET = 'bg-violet-100 text-violet-600';
    case FUCHSIA = 'bg-fuchsia-100 text-fuchsia-600';
    case SKY = 'bg-sky-100 text-sky-600';
    case STONE = 'bg-stone-100 text-stone-600';
    case NEUTRAL = 'bg-neutral-100 text-neutral-600';
    case ZINC = 'bg-zinc-100 text-zinc-600';

    /**
     * Get all available colors as an array.
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get a random color.
     */
    public static function random(): self
    {
        $colors = self::cases();

        return $colors[array_rand($colors)];
    }

    /**
     * Get color by index (for consistent assignment).
     */
    public static function byIndex(int $index): self
    {
        $colors = self::cases();

        return $colors[$index % count($colors)];
    }

    /**
     * Get color name for display.
     */
    public function name(): string
    {
        return match ($this) {
            self::INDIGO => 'Indigo',
            self::PURPLE => 'Purple',
            self::PINK => 'Pink',
            self::BLUE => 'Blue',
            self::GREEN => 'Green',
            self::YELLOW => 'Yellow',
            self::RED => 'Red',
            self::ORANGE => 'Orange',
            self::TEAL => 'Teal',
            self::CYAN => 'Cyan',
            self::EMERALD => 'Emerald',
            self::LIME => 'Lime',
            self::AMBER => 'Amber',
            self::ROSE => 'Rose',
            self::VIOLET => 'Violet',
            self::FUCHSIA => 'Fuchsia',
            self::SKY => 'Sky',
            self::STONE => 'Stone',
            self::NEUTRAL => 'Neutral',
            self::ZINC => 'Zinc',
        };
    }
}
