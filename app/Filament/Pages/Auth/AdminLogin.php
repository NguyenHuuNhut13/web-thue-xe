<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

class AdminLogin extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        if (!auth()->check()) {
            redirect('/member/login');
        }
    }
}
