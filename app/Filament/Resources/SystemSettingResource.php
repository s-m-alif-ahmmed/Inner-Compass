<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SystemSettingResource\Pages;
use App\Filament\Resources\SystemSettingResource\RelationManagers;
use App\Models\SystemSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SystemSettingResource extends Resource
{
    protected static ?string $model = SystemSetting::class;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'system_name', 'email', 'number', 'copyright_text', 'address', 'description'];
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number')
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('system_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('copyright_text')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/system_settings')
                    ->visibility('public')
                    ->maxSize(2048),
                Forms\Components\FileUpload::make('favicon')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/system_settings')
                    ->visibility('public')
                    ->maxSize(2048),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('logo')->height(50)->width('auto'),
                Tables\Columns\ImageColumn::make('favicon')->height(50)->width('auto'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
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
            'index' => Pages\ListSystemSettings::route('/'),
            'view' => Pages\ViewSystemSetting::route('/{record}'),
            'edit' => Pages\EditSystemSetting::route('/{record}/edit'),
        ];
    }
}
