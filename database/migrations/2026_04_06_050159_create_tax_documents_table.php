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
        Schema::create('tax_documents', function (Blueprint $table) {
            $table->id();
            $table->enum('tax_type', ['ghar_patti', 'paani_patti', 'other'])
                  ->comment('ghar_patti=घरपट्टी, paani_patti=पाणीपट्टी, other=गाळाभाडे/व्यवसायकर/इतर');
            $table->enum('document_type', ['view_pdf', 'payment_qr'])
                  ->comment('view_pdf=PDF पहा, payment_qr=QR पेमेंट');
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_documents');
    }
};
