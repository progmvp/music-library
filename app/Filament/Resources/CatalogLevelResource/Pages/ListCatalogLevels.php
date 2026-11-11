<?php

namespace App\Filament\Resources\CatalogLevelResource\Pages;

use App\Filament\Resources\CatalogLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatalogLevels extends ListRecords
{
    protected static string $resource = CatalogLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
