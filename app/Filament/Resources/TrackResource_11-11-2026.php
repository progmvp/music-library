<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackResource\Pages;
use App\Models\CatalogFolder;
use App\Models\CatalogLevel;
use App\Models\Track;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TrackResource extends Resource
{
    protected static ?string $model = Track::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $navigationLabel = 'Треки';

    protected static ?string $pluralModelLabel = 'Треки';

    protected static ?string $modelLabel = 'Трек';

    protected static ?string $navigationGroup = 'Музыкальный каталог';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Основные данные')
                    ->schema([

                        Forms\Components\TextInput::make('article')
                            ->label('Артикул')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->regex('/^[a-z0-9]+$/')
                            ->helperText('Например: tsfhv201'),

                        Forms\Components\TextInput::make('track_name')
                            ->label('Служебное имя трека')
                            ->required()
                            ->maxLength(255)
                            ->rule('not_regex:/\.(mp3|flac|wav)$/i')
                            ->helperText('Без расширения файла'),

                        Forms\Components\Select::make('section_id')
                            ->label('Раздел')
                            ->relationship('section', 'name')
                            ->required()
                            ->live()
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (callable $set) {
                                $set('folder_id', null);
                                $set('levels', []);
                            }),

                        Forms\Components\Select::make('folder_id')
                            ->label('Папка')
                            ->required()
                            ->searchable()
                            ->options(function (callable $get) {

                                if (!$get('section_id')) {
                                    return [];
                                }

                                return CatalogFolder::query()
                                    ->where('section_id', $get('section_id'))
                                    ->where('is_active', true)
                                    ->orderBy('folder_code')
                                    ->pluck('folder_code', 'id');
                            }),

                        Forms\Components\Select::make('levels')
                            ->label('Разряды')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship(
                                name: 'levels',
                                titleAttribute: 'level_name',
                                modifyQueryUsing: fn (
                                    Builder $query,
                                    callable $get
                                ) => $query
                                    ->where(
                                        'section_id',
                                        $get('section_id') ?: 0
                                    )
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                            ),

                    ])
                    ->columns(2),

                Forms\Components\Section::make('Музыка')
                    ->schema([

                        Forms\Components\TextInput::make('duration')
                            ->label('Длительность (сек.)')
                            ->numeric()
                            ->minValue(30)
                            ->maxValue(1000)
                            ->required(),

                        Forms\Components\Select::make('musicStyles')
                            ->label('Стили музыки')
                            ->relationship('musicStyles', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('year')
                            ->label('Год')
                            ->numeric()
                            ->minValue(2012)
                            ->maxValue(2035)
                            ->required(),

                    ])
                    ->columns(3),

                Forms\Components\Section::make('Стоимость')
                    ->schema([

                        Forms\Components\TextInput::make('price_mp3')
                            ->label('Цена MP3')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('price_flac')
                            ->label('Цена FLAC')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('price_bundle')
                            ->label('Цена Комплект')
                            ->numeric()
                            ->required(),

                    ])
                    ->columns(3),

                Forms\Components\Section::make('Дополнительно')
                    ->schema([

                        Forms\Components\TextInput::make('preview_code')
                            ->label('Код демо-ссылки')
                            ->required()
                            ->regex('/^[a-zA-Z0-9]+$/')
                            ->maxLength(20)
                            ->helperText('Например: 3e42'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true),

                    ])
                    ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('article')
                    ->label('Артикул')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('track_name')
                    ->label('Служебное имя')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('section.name')
                    ->label('Раздел')
                    ->sortable(),

                Tables\Columns\TextColumn::make('folder.folder_code')
                    ->label('Папка')
                    ->sortable(),

                Tables\Columns\TextColumn::make('levels.level_name')
                    ->label('Разряды')
                    ->badge(),

                Tables\Columns\TextColumn::make('musicStyles.name')
                    ->label('Стили')
                    ->badge(),

                Tables\Columns\TextColumn::make('price_mp3')
                    ->label('MP3'),

                Tables\Columns\TextColumn::make('price_flac')
                    ->label('FLAC'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('section_id')
                    ->relationship('section', 'name')
                    ->label('Раздел'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTracks::route('/'),
            'create' => Pages\CreateTrack::route('/create'),
            'edit' => Pages\EditTrack::route('/{record}/edit'),
        ];
    }
}
