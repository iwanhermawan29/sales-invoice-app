<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('targets_penjualan', function (Blueprint $table) {
            $table->id();

            // Agen wajib
            $table->foreignId('agent_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Produk opsional (null = target agregat semua produk)
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // Periode & rentang tanggal
            $table->enum('period', ['monthly', 'quarterly', 'annual'])->default('monthly');
            $table->date('start_date');
            $table->date('end_date');

            // KPI
            $table->decimal('target_premium', 15, 2)->default(0);
            $table->unsignedInteger('target_case')->default(0);

            // Info tambahan
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['agent_id', 'product_id']);
            $table->index(['period', 'start_date', 'end_date']);

            // Jika mau cegah duplikasi window target:
            // $table->unique(['agent_id','product_id','period','start_date','end_date'], 'targets_penjualan_unique_window');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('targets_penjualan');
    }
};
