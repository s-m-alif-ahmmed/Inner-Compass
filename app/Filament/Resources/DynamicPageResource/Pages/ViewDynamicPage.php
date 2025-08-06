<?php

namespace App\Filament\Resources\DynamicPageResource\Pages;

use App\Filament\Resources\DynamicPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDynamicPage extends ViewRecord
{
    protected static string $resource = DynamicPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
