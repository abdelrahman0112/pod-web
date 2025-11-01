<?php

namespace App\Filament\Widgets;

use App\JobApplicationStatus;
use App\Models\JobApplication;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentJobApplicationsPending extends BaseWidget
{
    protected static ?string $heading = 'Job Applications Requiring Action';

    protected int|string|array $columnSpan = [
        'xl' => 12,
    ];

    protected static ?int $sort = 6;

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Applicant')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jobListing.title')
                    ->label('Job')
                    ->limit(30),
                Tables\Columns\TextColumn::make('jobListing.company_name')
                    ->label('Company')
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->color(fn ($record) => match ($record->status) {
                        JobApplicationStatus::PENDING => 'warning',
                        JobApplicationStatus::REVIEWED => 'info',
                        JobApplicationStatus::ACCEPTED => 'success',
                        JobApplicationStatus::REJECTED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state?->getLabel() ?? 'Unknown'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->label('Applied')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->recordUrl(fn ($record) => route('filament.admin.resources.job-applications.edit', $record))
            ->actions([
                Tables\Actions\Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (JobApplication $record) {
                        $record->accept();
                        \Filament\Notifications\Notification::make()
                            ->title('Application Accepted')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (JobApplication $record) => in_array($record->status, [JobApplicationStatus::PENDING, JobApplicationStatus::REVIEWED])),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (JobApplication $record) {
                        $record->reject();
                        \Filament\Notifications\Notification::make()
                            ->title('Application Rejected')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (JobApplication $record) => $record->status !== JobApplicationStatus::REJECTED),
                Tables\Actions\Action::make('edit')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('filament.admin.resources.job-applications.edit', $record)),
            ]);
    }

    protected function getQuery(): Builder
    {
        return JobApplication::query()
            ->with(['user', 'jobListing'])
            ->whereIn('status', [JobApplicationStatus::PENDING->value, JobApplicationStatus::REVIEWED->value])
            ->latest();
    }
}
