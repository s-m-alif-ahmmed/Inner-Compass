<?php

namespace App\Filament\Resources\AudioResource\Pages;

use App\Filament\Resources\AudioResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAudio extends ViewRecord
{
    protected static string $resource = AudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
