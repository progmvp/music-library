<?php

namespace App\Filament\Resources\CatalogMusicStyleResource\Pages;

use App\Filament\Resources\CatalogMusicStyleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatalogMusicStyles extends ListRecords
{
    protected static string $resource = CatalogMusicStyleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
