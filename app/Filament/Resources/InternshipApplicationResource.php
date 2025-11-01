<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InternshipApplicationResource\Pages;
use App\GraduationStatus;
use App\InternshipApplicationStatus;
use App\Models\InternshipApplication;
use App\Models\InternshipCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InternshipApplicationResource extends Resource
{
    protected static ?string $model = InternshipApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Internships';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label('Full Name')
                            ->readOnly()
                            ->extraAttributes(['class' => 'focus:ring-0 focus:border-gray-300']),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->readOnly()
                            ->extraAttributes(['class' => 'focus:ring-0 focus:border-gray-300']),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->readOnly()
                            ->extraAttributes(['class' => 'focus:ring-0 focus:border-gray-300']),
                        Forms\Components\TextInput::make('university')
                            ->label('University/Institution')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('major')
                            ->label('Major/Field of Study')
                            ->maxLength(255),
                        Forms\Components\Select::make('graduation_status')
                            ->label('Graduation Status')
                            ->options(GraduationStatus::options())
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Experience')
                    ->schema([
                        Forms\Components\Textarea::make('experience')
                            ->label('Previous Experience')
                            ->rows(4)
                            ->maxLength(2000)
                            ->readOnly()
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'focus:ring-0 focus:border-gray-300'])
                            ->helperText('Describe any previous internships, projects, or relevant work experience...'),
                    ]),

                Forms\Components\Section::make('Areas of Interest')
                    ->schema([
                        Forms\Components\CheckboxList::make('interest_categories')
                            ->label('Select one or more categories that interest you')
                            ->options(function () {
                                return InternshipCategory::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->required()
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Availability')
                    ->schema([
                        Forms\Components\DatePicker::make('availability_start')
                            ->label('Available Start Date')
                            ->required(),
                        Forms\Components\DatePicker::make('availability_end')
                            ->label('Available End Date')
                            ->required()
                            ->after('availability_start'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Motivation')
                    ->schema([
                        Forms\Components\Textarea::make('motivation')
                            ->label('Why do you want to join our internship program?')
                            ->rows(6)
                            ->maxLength(2000)
                            ->readOnly()
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'focus:ring-0 focus:border-gray-300']),
                    ]),

                Forms\Components\Section::make('Admin Fields')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(InternshipApplicationStatus::options())
                            ->required(),
                        Forms\Components\Textarea::make('admin_response')
                            ->label('Rejection Reason')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')->searchable(),
                Tables\Columns\TextColumn::make('internship_id')
                    ->label('Internship')
                    ->searchable(query: function ($query, string $search) {
                        return $query->where(function ($q) use ($search) {
                            $q->whereHas('internship', function ($subQ) use ($search) {
                                $subQ->where('title', 'like', "%{$search}%");
                            })->orWhereNull('internship_id');
                        });
                    })
                    ->sortable()
                    ->html()
                    ->getStateUsing(function ($record) {
                        //
                        // ication - show as grey badge
                        if (empty($record->internship_id)) {
                            return '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">General Application</span>';
                        }

                        // Load internship if not already loaded
                        if (! $record->relationLoaded('internship')) {
                            $record->load('internship');
                        }

                        // If still no internship after loading, show general application
                        if (! $record->internship) {
                            return '<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">General Application</span>';
                        }

                        // Internship application - show as orange link with hover underline (open frontend page)
                        $title = $record->internship->title ?? 'N/A';
                        $url = route('internships.show', $record->internship);

                        return '<a href="'.e($url).'" target="_blank" style="color: #ea580c; text-decoration: none;" class="hover:underline cursor-pointer font-medium">'.e($title).'</a>';
                    }),
                Tables\Columns\TextColumn::make('user.email')->label('Applicant Email')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($record) => match ($record->status) {
                        InternshipApplicationStatus::PENDING => 'warning',
                        InternshipApplicationStatus::REVIEWED => 'info',
                        InternshipApplicationStatus::ACCEPTED => 'success',
                        InternshipApplicationStatus::REJECTED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state?->getLabel() ?? 'Unknown'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->modifyQueryUsing(function ($query) {
                $query->with('internship');
            })
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(InternshipApplicationStatus::options()),
            ])
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
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason (Optional)')
                            ->maxLength(255)
                            ->helperText('You can optionally provide a reason for rejection.'),
                    ])
                    ->action(function (InternshipApplication $record, array $data) {
                        $updateData = [
                            'status' => InternshipApplicationStatus::REJECTED,
                            'admin_id' => auth()->id(),
                        ];

                        // Only update admin_response if rejection reason is provided
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
            'index' => Pages\ListInternshipApplications::route('/'),
            'edit' => Pages\EditInternshipApplication::route('/{record}/edit'),
        ];
    }
}
