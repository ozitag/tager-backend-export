<?php

namespace OZiTAG\Tager\Backend\Export;

use Illuminate\Support\ServiceProvider;

class ExportServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }
}
