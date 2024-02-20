<?php

namespace Your\Package\Namespace;

use Illuminate\Support\ServiceProvider;
use Your\Package\Namespace\Services\{AssistantService, DatabaseExportService, FileDownloadService, MessageService, MyOpenAIService, RunService, ThreadService};
use Your\Package\Namespace\Models\User;

class LaravelChatbotServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load the routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        
        // Load views if your package has views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravelchatbot');

        // Publish models, config, views, and other resources
        $this->publishes([
            __DIR__.'/Models/User.php' => app_path('Models/LaravelChatbot/User.php'),
            __DIR__.'/config/laravelchatbot.php' => config_path('laravelchatbot.php'),
        ], 'laravelchatbot');

        // Publish migrations
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/database/migrations/' => database_path('migrations'),
            ], 'laravelchatbot-migrations');
        }
    }
    
    public function register()
    {
        // First, register MyOpenAIService as a singleton
        $this->app->singleton(MyOpenAIService::class, function ($app) {
            return new MyOpenAIService();
        });

        // Resolve MyOpenAIService from the container and pass it to other services
        $myOpenAIService = $this->app->make(MyOpenAIService::class);

        $this->app->singleton(AssistantService::class, function ($app) use ($myOpenAIService) {
            return new AssistantService($myOpenAIService);
        });

        $this->app->singleton(DatabaseExportService::class, function ($app) use ($myOpenAIService) {
            return new DatabaseExportService($myOpenAIService);
        });

        $this->app->singleton(FileDownloadService::class, function ($app) use ($myOpenAIService) {
            return new FileDownloadService($myOpenAIService);
        });

        $this->app->singleton(MessageService::class, function ($app) use ($myOpenAIService) {
            return new MessageService($myOpenAIService);
        });

        $this->app->singleton(RunService::class, function ($app) use ($myOpenAIService) {
            return new RunService($myOpenAIService);
        });

        $this->app->singleton(ThreadService::class, function ($app) use ($myOpenAIService) {
            return new ThreadService($myOpenAIService);
        });

        // You may also bind interfaces to implementations here
    }
}

// Remember to replace `Your\Package\Namespace` with the actual namespace of your package.
