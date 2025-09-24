<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 150);
            $table->date('sale_date');
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedTinyInteger('case_level')->comment('1,2,3'); // tanpa ->check()
            $table->decimal('premium', 15, 2);
            $table->timestamps();

            $table->index(['sale_date']);
            $table->index(['product_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
