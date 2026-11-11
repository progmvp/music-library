<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogFolderResource\Pages;
use App\Models\CatalogFolder;
use App\Models\CatalogSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CatalogFolderResource extends Resource
{
    protected static ?string $model = CatalogFolder::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Папки';

    protected static ?string $pluralModelLabel = 'Папки';

    protected static ?string $modelLabel = 'Папка';

    protected static ?string $navigationGroup = 'Музыкальный каталог';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('section_id')
                    ->label('Раздел')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('folder_code')
                    ->label('Код папки')
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('folder_name')
                    ->label('Название папки')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_active')
                    ->label('Активна')
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

                Tables\Columns\TextColumn::make('folder_code')
                    ->label('Код')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('folder_name')
                    ->label('Название')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('folder_code');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogFolders::route('/'),
            'create' => Pages\CreateCatalogFolder::route('/create'),
            'edit' => Pages\EditCatalogFolder::route('/{record}/edit'),
        ];
    }
}