<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class EditProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-0-user';
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.edit-profile';

    public $name;
    public $email;

    public function mount()
    {
        $user = Auth::user();

        // Populate form with the authenticated user's details
        $this->name = $user->name;
        $this->email = $user->email;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->columnSpanFull()
                ->disabled()
                ->required(),
        ];
    }

    protected function handleRecordUpdate(Authenticatable $record, array $data): Authenticatable
    {
        $record->update($data);
        return $record;
    }

    public function save()
    {
        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        Notification::make()
            ->body("Profile updated successfully!")
            ->success()
            ->send();
    }
}
