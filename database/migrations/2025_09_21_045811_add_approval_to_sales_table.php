<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // 0=pending, 1=approved, 2=rejected
            $table->unsignedTinyInteger('status')->default(0)->after('premium');
            $table->foreignId('approved_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->string('approval_note', 255)->nullable()->after('approved_at');

            $table->index(['status', 'sale_date']);
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['status', 'sale_date']);
            $table->dropColumn(['status', 'approved_by', 'approved_at', 'approval_note']);
        });
    }
};
