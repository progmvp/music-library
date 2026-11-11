<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogLevelResource\Pages;
use App\Models\CatalogLevel;
use App\Models\CatalogSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CatalogLevelResource extends Resource
{
    protected static ?string $model = CatalogLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Разряды';

    protected static ?string $pluralModelLabel = 'Разряды';

    protected static ?string $modelLabel = 'Разряд';

    protected static ?string $navigationGroup = 'Музыкальный каталог';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('section_id')
                    ->label('Раздел')
                    ->options(
                        CatalogSection::query()
                            ->where('is_active', true)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('level_name')
                    ->label('Название разряда')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Порядок сортировки')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('section.name')
                    ->label('Раздел')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('level_name')
                    ->label('Разряд')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Сортировка')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('section_id')
                    ->label('Раздел')
                    ->options(
                        CatalogSection::query()
                            ->pluck('name', 'id')
                    ),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogLevels::route('/'),
            'create' => Pages\CreateCatalogLevel::route('/create'),
            'edit' => Pages\EditCatalogLevel::route('/{record}/edit'),
        ];
    }
}