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
        Schema::create('country_file_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');  
            $table->string('slug')->unique();
            $table->foreignId('country_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('country_file_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['country_file_category_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_file_types');
    }
};
