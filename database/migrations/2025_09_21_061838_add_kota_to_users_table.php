<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // letakkan setelah address (ubah posisinya jika perlu)
            $table->string('kota', 100)->nullable()->after('address');
            // kalau kamu pakai index pencarian kota:
            // $table->index('kota');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // kalau tadi buat index, drop dulu indexnya:
            // $table->dropIndex(['kota']);
            $table->dropColumn('kota');
        });
    }
};
