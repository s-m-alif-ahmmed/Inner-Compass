<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AudioResource\Pages;
use App\Filament\Resources\AudioResource\RelationManagers;
use App\Models\Audio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\TemporaryUploadedFile;

class AudioResource extends Resource
{
    protected static ?string $model = Audio::class;

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
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/thumbnail')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->required(),
                Forms\Components\FileUpload::make('audio')
                    ->disk('public')
                    ->directory('uploads/audio')
                    ->visibility('public')
                    ->required()
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
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->height(100),
                Tables\Columns\TextColumn::make('duration')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->width('100px')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ])
                    ->default(fn ($record) => $record?->status ?? 'Active')
                    ->inline()
                    ->sortable()
                    ->searchable()
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->body("Product {$record->title} status changed to {$state}.")
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAudio::route('/'),
            'create' => Pages\CreateAudio::route('/create'),
            'view' => Pages\ViewAudio::route('/{record}'),
            'edit' => Pages\EditAudio::route('/{record}/edit'),
        ];
    }
}
