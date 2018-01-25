<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Desssa form é executada antes do carregamento da migration,
        // o que pode resultar em falha de tabela não existente durante alguns testes
        // \View::share('channels', Channel::all());

        
        // Executa após a view ser carregada
        // * identifica que deve ocorrer para todas as páginas,
        // no lugar pode ser passado um array de strings referentes as páginas que essa chamada deve ocorrer
        \View::composer('*', function ($view) {
            $channels = \Cache::rememberForever('channels', function () {
                return Channel::all();
            });

            $view->with('channels', $channels);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
