<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            // Kota untuk filter (index supaya pencarian cepat)
            $table->string('city', 60)->index(); // contoh: "Jakarta", "Medan", dsb.

            // Metadata konten
            $table->string('title', 150)->nullable();   // judul foto (opsional)
            $table->text('caption')->nullable();        // deskripsi/caption (opsional)

            // File foto
            $table->string('photo_path');               // path relatif di storage (e.g. "galleries/xxx.jpg")
            $table->string('photo_mime', 80)->nullable();
            $table->unsignedBigInteger('photo_size')->nullable(); // ukuran byte

            // Tanggal pengambilan dokumentasi (opsional)
            $table->date('taken_at')->nullable();

            // Relasi opsional ke kontes (kalau mau mengaitkan dokumentasi dengan kontes tertentu)
            $table->foreignId('contest_id')->nullable()
                ->constrained('contests')->nullOnDelete();

            // Siapa yang upload (opsional)
            $table->foreignId('uploaded_by')->nullable()
                ->constrained('users')->nullOnDelete();

            // Publish flag
            $table->boolean('is_published')->default(true)->index();

            $table->timestamps();
            $table->softDeletes();

            // Index tambahan yang berguna untuk listing dan filter
            $table->index(['contest_id', 'taken_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
