<?php

namespace App\Filament\Resources\DynamicPageResource\Pages;

use App\Filament\Resources\DynamicPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDynamicPages extends ListRecords
{
    protected static string $resource = DynamicPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
