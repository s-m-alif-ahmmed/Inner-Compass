<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DynamicPageResource\Pages;
use App\Filament\Resources\DynamicPageResource\RelationManagers;
use App\Models\DynamicPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class DynamicPageResource extends Resource
{
    protected static ?string $model = DynamicPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('page_title')
                    ->label('Page Title')
                    ->required()
                    ->maxLength(255)
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('page_slug', Str::slug($state))),
                Forms\Components\TextInput::make('page_slug')
                    ->label('Page Slug')
                    ->disabled()
                    ->required()
                    ->unique(DynamicPage::class, 'page_slug', fn ($record) => $record ?? null)
                    ->dehydrated(),
                Forms\Components\RichEditor::make('page_content')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page_title')
                    ->label('Page Title')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('page_slug')
                    ->label('Page Slug')
                    ->limit(20)
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
            'index' => Pages\ListDynamicPages::route('/'),
            'create' => Pages\CreateDynamicPage::route('/create'),
            'view' => Pages\ViewDynamicPage::route('/{record}'),
            'edit' => Pages\EditDynamicPage::route('/{record}/edit'),
        ];
    }
}
