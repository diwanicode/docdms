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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short');             
            $table->string('phone_code'); // e.g. +387
            $table->string('continent')->nullable(); // e.g. Europe
            $table->string('capital')->nullable();
            $table->string('currency')->nullable(); // e.g. BAM
            $table->string('flag')->nullable(); 
            $table->boolean('has_trunk_prefix')->default(false);  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
