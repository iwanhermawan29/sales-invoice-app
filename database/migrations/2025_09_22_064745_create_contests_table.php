<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->id();

            $table->string('nama_kontes');
            $table->unsignedBigInteger('target_premi')->default(0); // dalam rupiah
            $table->unsignedInteger('target_case')->default(0);

            // Pakai enum (MySQL) atau ubah ke string + check kalau perlu portable
            $table->enum('periode', ['monthly', 'quarterly', 'annual'])
                ->comment('monthly=bulanan, quarterly=kuartal, annual=tahunan');

            // Info file flyer (opsional tapi berguna)
            $table->string('flyer_path')->nullable();   // lokasi file di storage
            $table->string('flyer_mime', 50)->nullable();
            $table->unsignedInteger('flyer_size')->nullable(); // dalam KB

            $table->timestamps();

            $table->index(['periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contests');
    }
};
