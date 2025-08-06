<?php

namespace App\Filament\Resources\WelcomeAudioResource\Pages;

use App\Filament\Resources\WelcomeAudioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWelcomeAudio extends EditRecord
{
    protected static string $resource = WelcomeAudioResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}
