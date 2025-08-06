<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ChangePassword;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\EditProfile;
use App\Models\SystemSetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        try {
            $system = SystemSetting::first();
        }catch (\Exception $error){
            $system = null;
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandLogoHeight('3rem')
            ->brandName('Inner Compass')
//            ->brandLogo(asset($system?->logo ?? '/frontend/logo2.png'))
            ->favicon(asset($system?->favicon ?? '/frontend/favicon2.png'))
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Edit Profile')
                    ->url(fn (): string => EditProfile::getUrl())
                    ->icon('heroicon-o-user'),
                MenuItem::make()
                    ->label('Change Password')
                    ->url(fn (): string => ChangePassword::getUrl())
                    ->icon('heroicon-o-lock-closed'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
