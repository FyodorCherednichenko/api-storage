<?php

namespace App\Providers;

use App\Models\UserFile;
use App\QueryBuilders\UserFileQueryBuilder;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(UserFileQueryBuilder::class, function () {
            return (new UserFile())->newQuery();
        });
    }
}
