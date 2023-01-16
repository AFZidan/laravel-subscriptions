<?php

declare(strict_types=1);

namespace AFZidan\Subscriptions\Providers;

use AFZidan\Subscriptions\Models\Plan;
use Illuminate\Support\ServiceProvider;
use AFZidan\Support\Traits\ConsoleTools;
use AFZidan\Subscriptions\Models\Feature;
use AFZidan\Subscriptions\Models\PlanSubscription;
use AFZidan\Subscriptions\Models\PlanSubscriptionUsage;
use AFZidan\Subscriptions\Console\Commands\MigrateCommand;
use AFZidan\Subscriptions\Console\Commands\PublishCommand;
use AFZidan\Subscriptions\Console\Commands\RollbackCommand;

class SubscriptionsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.afzidan.subscriptions.migrate',
        PublishCommand::class => 'command.afzidan.subscriptions.publish',
        RollbackCommand::class => 'command.afzidan.subscriptions.rollback',
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'afzidan.subscriptions');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'afzidan.subscriptions.plan' => Plan::class,
            'afzidan.subscriptions.plan_feature' => Feature::class,
            'afzidan.subscriptions.plan_subscription' => PlanSubscription::class,
            'afzidan.subscriptions.plan_subscription_usage' => PlanSubscriptionUsage::class,
        ]);

        // Register console commands
        $this->registerCommands($this->commands);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish Resources
        $this->publishesConfig('afzidan/laravel-subscriptions');
        $this->publishesMigrations('afzidan/laravel-subscriptions');
        ! $this->autoloadMigrations('afzidan/laravel-subscriptions') || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
