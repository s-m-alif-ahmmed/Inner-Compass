<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WelcomeAudioResource\Pages;
use App\Filament\Resources\WelcomeAudioResource\RelationManagers;
use App\Models\WelcomeAudio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WelcomeAudioResource extends Resource
{
    protected static ?string $model = WelcomeAudio::class;

    // For hide the welcome audio navigation menu
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->maxLength(255)
                    ->readOnly(),
                Forms\Components\FileUpload::make('audio')
                    ->disk('public')
                    ->directory('uploads/welcome-audio')
                    ->visibility('public')
                    ->required()
                    ->columnSpanFull()
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $timestamp = now()->format('Ymd_His');
                        return $timestamp . '_' . $file->getClientOriginalName();
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state && method_exists($state, 'getRealPath')) {
                            $getID3 = new \getID3;
                            $fileInfo = $getID3->analyze($state->getRealPath());

                            if (!empty($fileInfo['playtime_seconds'])) {
                                $durationInSeconds = (int) $fileInfo['playtime_seconds'];
                                $minutes = floor($durationInSeconds / 60);
                                $seconds = $durationInSeconds % 60;
                                $formatted = sprintf('%02d:%02d', $minutes, $seconds);

                                $set('duration', $formatted);
                            }
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWelcomeAudio::route('/'),
            'view' => Pages\ViewWelcomeAudio::route('/{record}'),
            'edit' => Pages\EditWelcomeAudio::route('/{record}/edit'),
        ];
    }
}
