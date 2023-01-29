<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturePlanTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn(config('afzidan.subscriptions.tables.features'), 'plan_id')) {
            Schema::table(config('afzidan.subscriptions.tables.features'), function (Blueprint $table) {
                    $table->dropForeign(['plan_id']);
                    $table->dropColumn('plan_id');
                    $table->dropColumn('value');
                    $table->dropColumn('resettable_period');
                    $table->dropColumn('resettable_interval');
            });
        }
    }


}
