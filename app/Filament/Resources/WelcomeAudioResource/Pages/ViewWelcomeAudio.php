<?php

namespace App\Filament\Resources\WelcomeAudioResource\Pages;

use App\Filament\Resources\WelcomeAudioResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWelcomeAudio extends ViewRecord
{
    protected static string $resource = WelcomeAudioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
