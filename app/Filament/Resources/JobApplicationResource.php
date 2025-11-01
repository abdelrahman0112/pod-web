<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobApplicationResource\Pages;
use App\JobApplicationStatus;
use App\Models\JobApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobApplicationResource extends Resource
{
    protected static ?string $model = JobApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Jobs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Application Details')
                    ->schema([
                        Forms\Components\Select::make('job_listing_id')
                            ->label('Job Listing')
                            ->relationship('jobListing', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->label('Applicant')
                            ->relationship('user', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?? $record->email)
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options(JobApplicationStatus::options())
                            ->required(),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Application Content')
                    ->schema([
                        Forms\Components\Textarea::make('cover_letter')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('additional_info'),
                    ]),
                Forms\Components\Section::make('Admin Notes')
                    ->schema([
                        Forms\Components\Textarea::make('admin_notes')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Status Tracking')
                    ->schema([
                        Forms\Components\DateTimePicker::make('status_updated_at'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->with(['jobListing', 'user']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('jobListing.title')
                    ->label('Job Listing')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Applicant')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => route('profile.show.other', $record->user_id))
                    ->openUrlInNewTab()
                    ->formatStateUsing(fn ($record) => $record->user->name ?? 'N/A'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($record) => match ($record->status) {
                        JobApplicationStatus::PENDING => 'warning',
                        JobApplicationStatus::REVIEWED => 'info',
                        JobApplicationStatus::ACCEPTED => 'success',
                        JobApplicationStatus::REJECTED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state?->getLabel() ?? 'Unknown'),
                Tables\Columns\TextColumn::make('status_updated_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->accept();
                        \Filament\Notifications\Notification::make()
                            ->title('Application Accepted')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => in_array($record->status, [JobApplicationStatus::PENDING, JobApplicationStatus::REVIEWED])),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->reject();
                        \Filament\Notifications\Notification::make()
                            ->title('Application Rejected')
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status !== JobApplicationStatus::REJECTED),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobApplications::route('/'),
            'create' => Pages\CreateJobApplication::route('/create'),
            'edit' => Pages\EditJobApplication::route('/{record}/edit'),
        ];
    }
}
