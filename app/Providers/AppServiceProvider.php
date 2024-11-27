<?php

namespace App\Providers;

use App\Filament\MyLogoutResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Authenticated;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        //$loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(LogoutResponseContract::class, MyLogoutResponse::class);
        //Schema::enableForeignKeyConstraints();
        //Logout user dengan status user banned
        Event::listen(Authenticated::class, function ($event) {
            $user = $event->user;
            
            if ($user->isBanned()) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                
                abort(redirect()->route('login')
                    ->withErrors(['email' => 'Akun Anda telah diblokir. Silakan hubungi admin pada Whatsapp 08123456789 untuk informasi lebih lanjut.']));
            }

            if ($user->isNonactive()) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                
                abort(redirect()->route('login')
                    ->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi admin pada Whatsapp 08123456789 untuk mengaktifkan kembali.']));
            }
        });

        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // Add secure file routes
        Route::middleware(['web', 'auth'])
            ->prefix('secure')
            ->group(base_path('routes/secure.php'));
    }
}
