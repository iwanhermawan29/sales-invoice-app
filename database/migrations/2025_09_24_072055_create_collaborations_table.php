<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('collaborations', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // nama partner/brand
            $table->text('description')->nullable();      // keterangan
            $table->string('website_url')->nullable();    // link partner (opsional)
            $table->string('image_path')->nullable();     // path logo
            $table->string('image_mime')->nullable();
            $table->unsignedInteger('image_size')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collaborations');
    }
};
