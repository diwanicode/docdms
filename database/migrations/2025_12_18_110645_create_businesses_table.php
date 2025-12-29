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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');  
            $table->string('slug')->unique();
            $table->string('vat_number')->nullable();     
            $table->foreignId('business_type_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('language_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('country_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('country_region_id')->nullable()->constrained()->onDelete('cascade'); 
            $table->string('logo')->nullable();
            $table->boolean('visible')->default(true);
            $table->boolean('is_test')->default(false);  
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
