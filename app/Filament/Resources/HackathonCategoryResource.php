<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HackathonCategoryResource\Pages;
use App\Models\HackathonCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HackathonCategoryResource extends Resource
{
    protected static ?string $model = HackathonCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Categories';

    protected static ?string $navigationGroup = 'Hackathons';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hackathon Category Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\ColorPicker::make('color')
                            ->required()
                            ->default('#4F46E5'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->copyable()
                    ->copyMessage('Color copied')
                    ->copyMessageDuration(1500),
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
            'index' => Pages\ListHackathonCategories::route('/'),
            'create' => Pages\CreateHackathonCategory::route('/create'),
            'edit' => Pages\EditHackathonCategory::route('/{record}/edit'),
        ];
    }
}
