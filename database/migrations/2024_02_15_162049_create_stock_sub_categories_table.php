<?php

use App\Models\Company;
use App\Models\StockCategory;
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
        Schema::create('stock_sub_categories', function (Blueprint $table) {
            $table->id();
            $table -> foreignIdFor(Company::class);
            $table -> foreignIdFor(StockCategory::class);

            $table->text('name');
            $table->text('description')->nullable();
            $table->string('status')->nullable()->default('Active');
            $table->text('image')->nullable();
            $table->bigInteger('buying_price')->nullable()->default(0);
            $table->bigInteger('selling_price')->nullable()->default(0);
            $table->bigInteger('expected_profit')->nullable()->default(0);
            $table->bigInteger('earned_profit')->nullable()->default(0);

            $table -> string('measurement_unit');
            $table -> bigInteger('current_quantity')->nullable()->default(0);
            $table -> bigInteger('re0rder_level')->nullable()->default(0);
            $table->timestamps();
        });
    }

// name	
// description	
// status	
// image	
// buying_price	
// selling_price	
// expected_profit	
// earned_profit	
// measurement_unit	
// current_quantity	
// re0rder_level	
// created_at	
// updated_at	
// in_stock

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_sub_categories');
    }
};


