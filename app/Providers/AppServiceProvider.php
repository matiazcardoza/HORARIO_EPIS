<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Registrar todas las consultas SQL en los logs
        DB::listen(function ($query) {
            Log::info("Consulta SQL: " . $query->sql);
            Log::info("Bindings: " . implode(", ", $query->bindings));
            Log::info("Tiempo de ejecución: " . $query->time . " ms");
        });
    }
}
