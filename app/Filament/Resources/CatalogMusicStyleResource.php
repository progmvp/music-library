<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogMusicStyleResource\Pages;
use App\Models\CatalogMusicStyle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CatalogMusicStyleResource extends Resource
{
    protected static ?string $model = CatalogMusicStyle::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $navigationLabel = 'Стили музыки';

    protected static ?string $pluralModelLabel = 'Стили музыки';

    protected static ?string $modelLabel = 'Стиль музыки';

    protected static ?string $navigationGroup = 'Музыкальный каталог';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Название стиля')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Сортировка')
                    ->numeric()
                    ->default(100)
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
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Стиль музыки')
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
                //
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
            'index' => Pages\ListCatalogMusicStyles::route('/'),
            'create' => Pages\CreateCatalogMusicStyle::route('/create'),
            'edit' => Pages\EditCatalogMusicStyle::route('/{record}/edit'),
        ];
    }
}