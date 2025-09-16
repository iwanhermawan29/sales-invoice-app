<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            // Agen yang meng-input target
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Periode target (misal YYYY-MM)
            $table->string('period')
                ->comment('Format: YYYY-MM');

            // Nominal target
            $table->decimal('target_amount', 15, 2)
                ->comment('Target penjualan');

            // Catatan opsional
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
