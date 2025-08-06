<?php

namespace App\Filament\Resources\DynamicPageResource\Pages;

use App\Filament\Resources\DynamicPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDynamicPage extends CreateRecord
{
    protected static string $resource = DynamicPageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
