<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Tipe produk: health = Asuransi Kesehatan, life = Asuransi Jiwa
            $table->enum('type', ['health', 'life'])
                ->comment('health = Kesehatan, life = Jiwa');
            $table->string('name')
                ->comment('Nama produk');
            $table->text('description')
                ->nullable()
                ->comment('Deskripsi produk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
