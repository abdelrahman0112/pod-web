<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Author')
                            ->relationship('user', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?? $record->email)
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->required()
                            ->disabled(fn ($record) => $record !== null),
                        Forms\Components\Select::make('type')
                            ->options([
                                'text' => 'Text',
                                'image' => 'Image',
                                'poll' => 'Poll',
                            ])
                            ->required()
                            ->disabled(fn ($record) => $record !== null),
                        Forms\Components\Textarea::make('content')
                            ->label('Content')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media & Poll')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Images')
                            ->image()
                            ->directory('posts/images')
                            ->disk('public')
                            ->visibility('public')
                            ->multiple()
                            ->maxFiles(10)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->imagePreviewHeight('200')
                            ->columnSpanFull()
                            ->visible(fn (Forms\Get $get) => $get('type') === 'image'),
                        Forms\Components\Repeater::make('poll_options')
                            ->label('Poll Options')
                            ->schema([
                                Forms\Components\TextInput::make('option')
                                    ->label('Option')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->defaultItems(2)
                            ->minItems(2)
                            ->maxItems(10)
                            ->columnSpanFull()
                            ->visible(fn (Forms\Get $get) => $get('type') === 'poll'),
                        Forms\Components\DateTimePicker::make('poll_ends_at')
                            ->label('Poll End Date & Time')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'poll'),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make('Tags')
                    ->schema([
                        Forms\Components\TagsInput::make('hashtags')
                            ->label('Hashtags')
                            ->placeholder('Add a hashtag (without #)')
                            ->separator(',')
                            ->splitKeys(['Tab', ','])
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('likes_count')
                            ->label('Likes Count')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('comments_count')
                            ->label('Comments Count')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('shares_count')
                            ->label('Shares Count')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Forms\Components\Section::make('Status & Visibility')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Whether this post is visible to users')
                            ->required()
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->helperText('Whether this post appears in featured section')
                            ->required()
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Content')
                    ->limit(80)
                    ->toggleable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        if (! $state) {
                            return null;
                        }

                        // Strip HTML tags and decode HTML entities to show plain text preview
                        return html_entity_decode(strip_tags($state), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }),
                Tables\Columns\TextColumn::make('likes_count')
                    ->label('Likes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comments_count')
                    ->label('Comments')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shares_count')
                    ->label('Shares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Add filters here if needed later
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(fn ($record) => route('filament.admin.resources.posts.edit', $record));
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
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
        return auth()->user()?->isAdmin() ?? false;
    }
}
