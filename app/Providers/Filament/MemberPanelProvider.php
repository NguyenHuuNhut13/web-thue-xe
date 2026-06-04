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

class MemberPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('member')
            ->path('member')
            ->login()
            ->registration()
            ->brandLogo(fn () => view('filament.logo'))
            ->font('Outfit')
            ->sidebarCollapsibleOnDesktop()
            ->profile(\App\Filament\Pages\EditProfile::class)
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Quay lại Trang chủ')
                    ->url('/')
                    ->icon('heroicon-o-home')
                    ->sort(-1),
            ])
            ->userMenuItems([
                'home' => \Filament\Navigation\MenuItem::make()
                    ->label('Trang chủ NKS')
                    ->url('/')
                    ->icon('heroicon-o-home'),
            ])
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'primary' => Color::hex('#0077bb'),
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Member/Resources'), for: 'App\Filament\Member\Resources')
            ->discoverPages(in: app_path('Filament/Member/Pages'), for: 'App\Filament\Member\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Member/Widgets'), for: 'App\Filament\Member\Widgets')
            ->widgets([
                AccountWidget::class,
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
                \App\Http\Middleware\RedirectAdminToAdminPanel::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
