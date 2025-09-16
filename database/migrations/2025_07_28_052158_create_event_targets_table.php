<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_targets', function (Blueprint $table) {
            $table->id();
            // Agen pemilik target
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            // Nama event tahunan
            $table->string('event_name')
                ->comment('Nama event tahunan');
            // Tahun target
            $table->year('year')
                ->comment('Tahun pelaksanaan event');
            // Nominal target
            $table->decimal('target_amount', 15, 2)
                ->comment('Target untuk event tahunan');
            $table->text('notes')
                ->nullable()
                ->comment('Keterangan tambahan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_targets');
    }
};
