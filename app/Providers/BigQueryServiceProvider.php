<?php

namespace App\Providers;

use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Support\ServiceProvider;

class BigQueryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(BigQueryClient::class, function ($app) {
            return new BigQueryClient([
                'keyFilePath' => env('GOOGLE_APPLICATION_CREDENTIALS'),
                'projectId' => env('GOOGLE_PROJECT_ID'),
            ]);
        });
    }

    public function boot()
    {
        //
    }
}
