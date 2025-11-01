<?php

namespace App\Filament\Widgets;

use App\InternshipApplicationStatus;
use App\Models\InternshipApplication;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentInternshipApplications extends BaseWidget
{
    protected static ?string $heading = 'Internship Applications Requiring Action';

    protected int|string|array $columnSpan = [
        'xl' => 12,
    ];

    protected static ?int $sort = 4;

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
                Tables\Columns\TextColumn::make('internship.title')
                    ->label('Internship')
                    ->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->color(fn ($record) => match ($record->status) {
                        InternshipApplicationStatus::PENDING => 'warning',
                        InternshipApplicationStatus::REVIEWED => 'info',
                        InternshipApplicationStatus::ACCEPTED => 'success',
                        InternshipApplicationStatus::REJECTED => 'danger',
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
            ->recordUrl(fn ($record) => route('filament.admin.resources.internship-applications.edit', $record))
            ->actions([
                Tables\Actions\Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (InternshipApplication $record) {
                        $record->update([
                            'status' => InternshipApplicationStatus::ACCEPTED,
                            'admin_id' => auth()->id(),
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Application Accepted')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (InternshipApplication $record) => in_array($record->status, [InternshipApplicationStatus::PENDING, InternshipApplicationStatus::REVIEWED])),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        \Filament\Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason (Optional)')
                            ->maxLength(255)
                            ->helperText('You can optionally provide a reason for rejection.'),
                    ])
                    ->action(function (InternshipApplication $record, array $data) {
                        $updateData = [
                            'status' => InternshipApplicationStatus::REJECTED,
                            'admin_id' => auth()->id(),
                        ];

                        if (! empty($data['rejection_reason'])) {
                            $updateData['admin_response'] = $data['rejection_reason'];
                        }

                        $record->update($updateData);
                        \Filament\Notifications\Notification::make()
                            ->title('Application Rejected')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (InternshipApplication $record) => in_array($record->status, [InternshipApplicationStatus::PENDING, InternshipApplicationStatus::REVIEWED])),

                Tables\Actions\Action::make('edit')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => route('filament.admin.resources.internship-applications.edit', $record)),
            ]);
    }

    protected function getQuery(): Builder
    {
        return InternshipApplication::query()
            ->with(['user', 'internship'])
            ->whereIn('status', [InternshipApplicationStatus::PENDING->value, InternshipApplicationStatus::REVIEWED->value])
            ->latest();
    }
}
