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
        Schema::create('business_files', function (Blueprint $table) {
            $table->id();
            $table->uuid('file_key')->unique();

            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_file_category_id')->constrained();
            $table->foreignId('country_file_type_id')->nullable()->constrained();
            $table->foreignId('country_file_status_id')->constrained()->restrictOnDelete();

            $table->foreignId('business_employee_id')->constrained()->onDelete('cascade');
          
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size'); // bytes

            $table->date('document_date')->nullable(); // invoice date, salary month
            $table->string('reference_number')->nullable(); // invoice no, report no
            $table->string('checksum', 64)->nullable();
          
            $table->string('source')->default('manual');   //manual.api, import, AI
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
            $table->timestamp('updated_at')->nullable()->change();
            $table->softDeletes();
            $table->index(['business_id','business_client_id', 'country_file_category_id'],'bf_business_client_category_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_files');
    }
};
