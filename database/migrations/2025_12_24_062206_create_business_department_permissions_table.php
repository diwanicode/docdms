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
        Schema::create('business_department_permissions', function (Blueprint $table) {
            $table->id();
             $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('business_department_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['permission_id', 'business_department_id'],'bf_business_permission_department_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_department_permissions');
    }
};
