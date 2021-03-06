<?php

namespace LArtie\TelegramBotMaker;

use Illuminate\Support\ServiceProvider;

use LArtie\TelegramBotMaker\Console\Commands\MakeBot;

class TelegramBotMakerServiceProvider extends ServiceProvider
{
    /**
     * List of artisan commands
     * @var array
     */
    protected $commands = [
        MakeBot::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}
