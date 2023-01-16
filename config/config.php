<?php

declare(strict_types=1);

return [

    // Manage autoload migrations
    'autoload_migrations' => true,

    // Subscriptions Database Tables
    'tables' => [

        'plans' => 'plans',
        'features' => 'features',
        'feature_plan' => 'feature_plan',
        'plan_subscriptions' => 'plan_subscriptions',
        'plan_subscription_usage' => 'plan_subscription_usage',

    ],

    // Subscriptions Models
    'models' => [

        'plan' => \AFZidan\Subscriptions\Models\Plan::class,
        'feature' => \AFZidan\Subscriptions\Models\Feature::class,
        'plan_subscription' => \AFZidan\Subscriptions\Models\PlanSubscription::class,
        'plan_subscription_usage' => \AFZidan\Subscriptions\Models\PlanSubscriptionUsage::class,

    ],

];
