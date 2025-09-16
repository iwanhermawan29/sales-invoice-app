<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_closings', function (Blueprint $table) {
            $table->id();
            // Transaksi ini terkait ke target tertentu
            $table->foreignId('sales_target_id')
                ->constrained('sales_targets')
                ->cascadeOnDelete();
            $table->string('customer')
                ->comment('Nama customer');
            $table->string('policy_number')
                ->comment('Nomor polis');
            $table->decimal('premium_amount', 15, 2)
                ->comment('Nilai premi closing');
            $table->date('closing_date')
                ->comment('Tanggal closing');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_closings');
    }
};
