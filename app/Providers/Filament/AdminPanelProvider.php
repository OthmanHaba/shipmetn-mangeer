<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentLanguageSwitch\FilamentLanguageSwitchPlugin;
use Filament\FontProviders\LocalFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\URL;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // if(app()->environment('production')){
        // }

        URL::forceScheme('https');

        return $panel
            ->brandLogo(asset('logo.svg'))
            ->brandLogoHeight('4rem')
            ->font(family: 'cairo', url: asset('css/font.css'), provider: LocalFontProvider::class)
            ->colors([
                'primary' => Color::Hex('#2bc2bf'),
                'secondary' => Color::Hex('#1b97a6'),
                // 'accent' => Color::Green,
                // 'info' => Color::Blue,
                // 'success' => Color::Green,
                // 'warning' => Color::Yellow,
            ])
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
            ->plugins([
                FilamentShieldPlugin::make(),
                // FilamentLanguageSwitchPlugin::make()
                //     ->locales(['en', 'ar'])
                //     ->displayLocale('en')
                //     ->renderHookName('panels::user-menu.before'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                __('general.navigation.shipments'),
                __('general.navigation.accounting'),
                __('general.navigation.users'),
            ]);
    }
}
