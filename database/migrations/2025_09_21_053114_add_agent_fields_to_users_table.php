<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Profil dasar agent
            $table->string('agency_name', 150)->nullable()->after('name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('address', 255)->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('address');
            $table->string('id_number', 50)->nullable()->after('birth_date'); // KTP/SIM/ID
            $table->string('bank_name', 100)->nullable()->after('id_number');
            $table->string('bank_account', 100)->nullable()->after('bank_name');

            // Verifikasi & kode agent
            // 0=pending, 1=approved, 2=rejected
            $table->unsignedTinyInteger('profile_status')->default(0)->after('remember_token');
            $table->string('kode_agent', 5)->unique()->nullable()->after('profile_status');
            $table->foreignId('profile_approved_by')->nullable()->constrained('users')->nullOnDelete()->after('kode_agent');
            $table->timestamp('profile_approved_at')->nullable()->after('profile_approved_by');
            $table->string('profile_approval_note', 255)->nullable()->after('profile_approved_at');

            $table->index(['profile_status']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['profile_status']);
            $table->dropConstrainedForeignId('profile_approved_by');
            $table->dropColumn([
                'agency_name',
                'phone',
                'address',
                'birth_date',
                'id_number',
                'bank_name',
                'bank_account',
                'profile_status',
                'kode_agent',
                'profile_approved_at',
                'profile_approval_note',
            ]);
        });
    }
};
