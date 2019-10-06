<?php


namespace Yjtec\AmqpLink;

use Illuminate\Support\ServiceProvider;

class ProduceTopicServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('produce', function () {
            return new Produce();
        });
        $this->app->singleton('consume', function () {
            return new Consume();
        });
    }
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/amqp.php' => config_path('amqp.php'),
        ]);
    }
}