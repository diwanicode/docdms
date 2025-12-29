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
        Schema::create('business_clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('client_key')->unique();
            $table->string('name');
            $table->string('contact');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('vat_number')->nullable();     
            $table->string('email')->nullable();
            $table->string('number')->nullable();  
            $table->foreignId('business_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('business_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('business_employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('country_id')->constrained()->onDelete('cascade'); 
            $table->string('city');
            $table->text('address');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_clients');
    }
};
