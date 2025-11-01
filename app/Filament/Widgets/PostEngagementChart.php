<?php

namespace App\Filament\Widgets;

use App\Models\Comment;
use App\Models\PostLike;
use App\Models\PostShare;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PostEngagementChart extends ChartWidget
{
    protected static ?string $heading = 'Posts Engagement Statistics';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'xl' => 12,
    ];

    protected static ?string $maxHeight = '320px';

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => ['display' => false],
                ],
                'y' => [
                    'grid' => ['display' => true],
                    'ticks' => ['precision' => 0],
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        // Get likes data over last 6 months
        $likesData = Trend::model(PostLike::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();

        // Get comments data over last 6 months
        $commentsData = Trend::model(Comment::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();

        // Get shares data over last 6 months
        $sharesData = Trend::model(PostShare::class)
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Likes',
                    'data' => $likesData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                    'fill' => false,
                ],
                [
                    'label' => 'Comments',
                    'data' => $commentsData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#059669',
                    'fill' => false,
                ],
                [
                    'label' => 'Shares',
                    'data' => $sharesData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                    'fill' => false,
                ],
            ],
            'labels' => $likesData->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
