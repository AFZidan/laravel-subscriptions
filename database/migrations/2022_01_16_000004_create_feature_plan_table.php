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
                if(Schema::hasColumn(config('afzidan.subscriptions.tables.features'),'plan_id')){

                    $table->dropForeign(['plan_id']);
                    $table->dropColumn('plan_id');
                }
            });
        }

        Schema::create(config('afzidan.subscriptions.tables.feature_plan'), function (Blueprint $table) {
            $table->integer('plan_id');
            $table->integer('feature_id');
            $table->string('value');
            $table->unsignedSmallInteger('resettable_period')->default(0);
            $table->string('resettable_interval')->default('month');
            
            $table->foreign('plan_id')
                ->references('id')
                ->on(config('afzidan.subscriptions.tables.plans'))
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('feature_id')
                ->references('id')
                ->on(config('afzidan.subscriptions.tables.features'))
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->primary(['plan_id', 'feature_id']);
            $table->unique(['plan_id', 'feature_id']);


        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('afzidan.subscriptions.tables.feature_plan'));
    }
}
