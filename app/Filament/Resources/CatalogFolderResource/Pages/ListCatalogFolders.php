<?php

namespace App\Filament\Resources\CatalogFolderResource\Pages;

use App\Filament\Resources\CatalogFolderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatalogFolders extends ListRecords
{
    protected static string $resource = CatalogFolderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
