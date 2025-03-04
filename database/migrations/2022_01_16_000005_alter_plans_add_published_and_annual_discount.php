<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlansAddPublishedAndAnnualDiscount extends Migration
{
    public function up(): void
    {
        Schema::table(config('afzidan.subscriptions.tables.plans'), function (Blueprint $table) {

            $table->float('annual_discount')->default(0)->after('price');
            $table->boolean('published')->default(true)->after('is_active');
        });
    }

}
