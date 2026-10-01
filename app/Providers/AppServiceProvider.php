<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use App\Models\Livro;
use App\Models\Sala;
use App\Models\Emprestimo;
use App\Models\ReservaSala;
use App\Observers\LivroObserver;
use App\Observers\SalaObserver;
use App\Observers\EmprestimoObserver;
use App\Observers\ReservaSalaObserver;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Livro::observe(LivroObserver::class);
        Sala::observe(SalaObserver::class);
        Emprestimo::observe(EmprestimoObserver::class);
        ReservaSala::observe(ReservaSalaObserver::class);
    }
}