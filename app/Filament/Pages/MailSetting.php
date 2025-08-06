<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms;
use Illuminate\Support\Facades\File;

class MailSetting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.mail-setting';

    protected static ?string $navigationGroup = 'Settings';

    public $mailer;
    public $host;
    public $port;
    public $username;
    public $form_address;
    public $password;
    public $encryption;

    public function mount()
    {
        $mail = \App\Models\MailSetting::first();

        // Populate form with the authenticated user's details
        $this->mailer = $mail->mailer ?? env('MAIL_MAILER');
        $this->host = $mail->host ?? env('MAIL_HOST');
        $this->port = $mail->port ?? env('MAIL_PORT');
        $this->username = $mail->username ?? env('MAIL_USERNAME');
        $this->form_address = $mail->form_address ?? env('MAIL_FROM_ADDRESS');
        $this->password = $mail->password ?? env('MAIL_PASSWORD');
        $this->encryption = $mail->encryption ?? env('MAIL_ENCRYPTION');
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('mailer')
                ->label('Mailer')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('host')
                ->label('Host')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('port')
                ->label('Port')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('username')
                ->label('User Name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('form_address')
                ->label('Form Address')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->required()
                ->password()
                ->revealable()
                ->maxLength(255),

            Forms\Components\TextInput::make('encryption')
                ->label('Encryption')
                ->required()
                ->maxLength(255),
        ];
    }

    protected function handleRecordUpdate($record, array $data)
    {
        $record->update($data);
        return $record;
    }

    public function save()
    {
        $mail = \App\Models\MailSetting::first();

        if (!$mail) {
            // Create new record if not found
            $mail = \App\Models\MailSetting::create([
                'mailer' => $this->mailer,
                'host' => $this->host,
                'port' => $this->port,
                'username' => $this->username,
                'form_address' => $this->form_address,
                'password' => $this->password,
                'encryption' => $this->encryption,
            ]);
        } else {
            // Update existing record
            $mail->update([
                'mailer' => $this->mailer,
                'host' => $this->host,
                'port' => $this->port,
                'username' => $this->username,
                'form_address' => $this->form_address,
                'password' => $this->password,
                'encryption' => $this->encryption,
            ]);
        }

        // Update .env file dynamically
        $this->updateEnvFile();

        Notification::make()
            ->body("Mail settings updated successfully!")
            ->success()
            ->send();
    }

    private function updateEnvFile()
    {
        $envContent = File::get(base_path('.env'));
        $lineBreak  = "\n";
        $envContent = preg_replace([
            '/MAIL_MAILER=(.*)\s*/',
            '/MAIL_HOST=(.*)\s*/',
            '/MAIL_PORT=(.*)\s*/',
            '/MAIL_USERNAME=(.*)\s*/',
            '/MAIL_PASSWORD=(.*)\s*/',
            '/MAIL_ENCRYPTION=(.*)\s*/',
            '/MAIL_FROM_ADDRESS=(.*)\s*/',
        ], [
            'MAIL_MAILER=' . $this->mailer . $lineBreak,
            'MAIL_HOST=' . $this->host . $lineBreak,
            'MAIL_PORT=' . $this->port . $lineBreak,
            'MAIL_USERNAME=' . $this->username . $lineBreak,
            'MAIL_PASSWORD=' . $this->password . $lineBreak,
            'MAIL_ENCRYPTION=' . $this->encryption . $lineBreak,
            'MAIL_FROM_ADDRESS=' . '"' . $this->form_address . '"' . $lineBreak,
        ], $envContent);

        File::put(base_path('.env'), $envContent);
    }

}
