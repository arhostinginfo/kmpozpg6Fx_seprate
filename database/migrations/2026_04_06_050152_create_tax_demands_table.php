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
        Schema::create('tax_demands', function (Blueprint $table) {
            $table->id();
            $table->enum('tax_type', ['ghar_patti', 'paani_patti', 'other'])
                  ->comment('ghar_patti=घरपट्टी, paani_patti=पाणीपट्टी, other=गाळाभाडे/व्यवसायकर/इतर');
            $table->enum('year_type', ['magil', 'chalu'])
                  ->comment('magil=मागील वर्ष, chalu=चालू वर्ष');
            $table->decimal('demand_amount', 14, 2)->default(0.00);
            $table->decimal('collected_amount', 14, 2)->default(0.00);
            $table->decimal('percentage', 6, 2)->default(0.00);
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
        Schema::dropIfExists('tax_demands');
    }
};
