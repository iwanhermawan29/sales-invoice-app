<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_contest_media_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contest_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contest_id')->constrained()->cascadeOnDelete();

            // photo | logo
            $table->enum('type', ['photo', 'logo'])->index();

            $table->string('title')->nullable();
            $table->string('caption')->nullable();

            $table->string('path');          // storage path (disk 'public')
            $table->string('mime', 80)->nullable();
            $table->unsignedInteger('size')->nullable(); // KB

            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contest_media');
    }
};
