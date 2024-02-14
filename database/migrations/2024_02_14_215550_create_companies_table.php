<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('owner_id');
            $table->text('name');
            $table->text('email')->nullable();
            $table->text('logo')->nullable();
            $table->text('website')->nullable();
            $table->text('about')->nullable();
            $table->string('status')->nullable();
            $table->text('address')->nullable();
            $table->date('license_expire')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('phone_number_2')->nullable();
            $table->text('pobox')->nullable();
            $table->text('color')->nullable();
            $table->text('slogan')->nullable();
            $table->text('facebook')->nullable();
            $table->text('twitter')->nullable();
            $table->string('currency')->default('USD')->nullable();
            $table->string('settings_worker_can_create_stock_item')->default('Yes')->nullable();
            $table->string('settings_worker_can_create_stock_record')->default('Yes')->nullable();
            $table->string('settings_worker_can_create_stock_category')->default('Yes')->nullable();
            $table->string('settings_worker_can_view_balance')->default('Yes')->nullable();
            $table->string('settings_worker_can_view_stats')->default('Yes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
