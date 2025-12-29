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
        Schema::create('permission_countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->onDelete('cascade'); 
            $table->string('resource');       // files
            $table->string('action');         // update
            $table->string('name');           // Update files
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['permission_id', 'country_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_countries');
    }
};
