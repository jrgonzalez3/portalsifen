<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\Width;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->maxContentWidth(Width::Full)  
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->globalSearch(false)
            ->brandName('Panel Sifen')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
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
            ])
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Documentacion Api Fac. Elect.')
                    ->url('http://apidoc.local:8080/#introduccion', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-document-text')
                    ->group('Utilidades')
                    ->sort(100),
                \Filament\Navigation\NavigationItem::make('Consultar datos de Personas')
                    ->url('http://panelsifen.local:8080/personas.php', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-users')
                    ->group('Utilidades')
                    ->sort(101),
                \Filament\Navigation\NavigationItem::make('Consultar Validez del XML Sifen')
                    ->url('https://ekuatia.set.gov.py/prevalidador/validacion', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-check-circle')
                    ->group('Utilidades')
                    ->sort(102),
            ]);
    }
}
