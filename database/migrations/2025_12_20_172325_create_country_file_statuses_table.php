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
        Schema::create('country_file_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // uploaded, processed, approved...
            $table->string('name');          // Human-readable
            $table->string('color_light'); // UI badge color
            $table->string('color_dark'); // UI badge color
            $table->unsignedInteger('order')->default(0);
            $table->foreignId('country_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('country_file_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('business_id')->nullable()->constrained()->onDelete('cascade'); 
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_file_statuses');
    }
};
