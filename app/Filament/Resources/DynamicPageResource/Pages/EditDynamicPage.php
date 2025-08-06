<?php

namespace App\Filament\Resources\DynamicPageResource\Pages;

use App\Filament\Resources\DynamicPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDynamicPage extends EditRecord
{
    protected static string $resource = DynamicPageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
