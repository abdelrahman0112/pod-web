<?php

namespace App\Filament\Resources;

use App\ExperienceLevel;
use App\Filament\Resources\JobListingResource\Pages;
use App\JobStatus;
use App\LocationType;
use App\Models\JobListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobListingResource extends Resource
{
    protected static ?string $model = JobListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Jobs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Company Details')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('company_description')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Job Details')
                    ->schema([
                        Forms\Components\Select::make('location_type')
                            ->options(LocationType::options())
                            ->required(),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('salary_min')
                            ->label('Salary Min')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('salary_max')
                            ->label('Salary Max')
                            ->maxLength(255),
                        Forms\Components\TagsInput::make('required_skills')
                            ->label('Required Skills')
                            ->placeholder('Add a skill (e.g. PHP, JavaScript, React)')
                            ->separator(',')
                            ->splitKeys(['Tab', ','])
                            ->required(),
                        Forms\Components\Select::make('experience_level')
                            ->options(ExperienceLevel::options())
                            ->required(),
                        Forms\Components\DatePicker::make('application_deadline')
                            ->required(),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Category & Status')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('status')
                            ->options(JobStatus::options())
                            ->required(),
                        Forms\Components\Select::make('posted_by')
                            ->label('Posted By')
                            ->relationship('poster', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?? $record->email)
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->with(['category', 'poster']);
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state?->getLabel() ?? 'N/A'),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('experience_level')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state?->getLabel() ?? 'N/A'),
                Tables\Columns\TextColumn::make('application_deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        if (! $state) {
                            return 'Unknown';
                        }

                        $statusEnum = JobStatus::tryFrom($state);

                        return $statusEnum?->getLabel() ?? ucfirst($state);
                    })
                    ->color(fn ($record) => match ($record->status) {
                        'active' => 'success',
                        'closed' => 'danger',
                        'archived' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('poster.name')
                    ->label('Posted By')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state, $record) => $record->poster->name ?? 'N/A'),
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
                Tables\Actions\Action::make('close')
                    ->label('Close')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (JobListing $record) {
                        $record->update(['status' => JobStatus::CLOSED->value]);
                        \Filament\Notifications\Notification::make()
                            ->title('Job Listing Closed')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (JobListing $record) => $record->status === JobStatus::ACTIVE->value),
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (JobListing $record) {
                        $record->update(['status' => JobStatus::ACTIVE->value]);
                        \Filament\Notifications\Notification::make()
                            ->title('Job Listing Activated')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (JobListing $record) => in_array($record->status, [JobStatus::CLOSED->value, JobStatus::ARCHIVED->value])),
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
            'index' => Pages\ListJobListings::route('/'),
            'create' => Pages\CreateJobListing::route('/create'),
            'edit' => Pages\EditJobListing::route('/{record}/edit'),
        ];
    }

    /**
     * Determine whether the user can view any models.
     */
    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    /**
     * Determine whether the user can create models.
     */
    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user && ($user->isAdmin() || $user->isClient());
    }
}
