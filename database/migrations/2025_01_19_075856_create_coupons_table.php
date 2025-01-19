<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed_amount']);
            $table->decimal('discount_value', 10, 2);
            $table->integer('usage_limit')->default(1);
            $table->integer('used_count')->default(0);
            $table->datetime('valid_from')->nullable();
            $table->datetime('valid_until')->nullable();
            $table->decimal('min_order_value', 10, 2)->nullable();
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->enum('status', ['active', 'expired', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
