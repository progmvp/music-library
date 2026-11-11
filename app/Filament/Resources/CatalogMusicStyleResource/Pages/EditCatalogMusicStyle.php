<?php

namespace App\Filament\Resources\CatalogMusicStyleResource\Pages;

use App\Filament\Resources\CatalogMusicStyleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatalogMusicStyle extends EditRecord
{
    protected static string $resource = CatalogMusicStyleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
