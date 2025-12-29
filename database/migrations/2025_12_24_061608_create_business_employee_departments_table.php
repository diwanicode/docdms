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
        Schema::create('business_employee_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('business_department_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        
            $table->unique(['business_employee_id', 'business_department_id'],'bf_business_employee_department_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_employee_departments');
    }
};
