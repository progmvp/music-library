<?php

namespace App\Filament\Resources\CatalogLevelResource\Pages;

use App\Filament\Resources\CatalogLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatalogLevel extends EditRecord
{
    protected static string $resource = CatalogLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
