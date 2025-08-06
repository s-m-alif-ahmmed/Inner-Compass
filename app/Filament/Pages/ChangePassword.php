<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.change-password';

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('current_password')
                ->label('Current Password')
                ->password()
                ->revealable()
                ->required(),
            TextInput::make('new_password')
                ->label('New Password')
                ->password()
                ->revealable()
                ->required(),
            TextInput::make('new_password_confirmation')
                ->label('Confirm New Password')
                ->password()
                ->revealable()
                ->required(),
        ];
    }

    public function savePassword()
    {
        // Validate password fields
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|same:new_password_confirmation',
        ]);

        $user = Auth::user();

        // Validate the current password
        if (!Hash::check($this->current_password, $user->password)) {
            Notification::make()
                ->body("Current password is incorrect.")
                ->danger()
                ->send();
            return;
        }

        // Update the password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // Clear password fields and show success message
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        Notification::make()
            ->body("Password updated successfully!")
            ->success()
            ->send();
    }
}
