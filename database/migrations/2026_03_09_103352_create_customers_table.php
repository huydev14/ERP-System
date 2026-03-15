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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->string('phone')->nullable();

            $table->string('address')->nullable();
            $table->string('source')->nullable();

            $table->unsignedTinyInteger('classify')->default(0)->comment('0: hết hàng, 1: còn hàng');
            $table->string('product_sale')->nullable();

            $table->unsignedTinyInteger('scale')->default(0)->comment('0: nhỏ, 1: vừa, 2: lớn');
            $table->foreignId('care_customer_id')->nullable()->constrained('care_customers')->cascadeOnUpdate()->nullOnDelete();

            $table->text('information');
            $table->unsignedTinyInteger('potential')->default(0)->comment('0: thấp, 1: trung bình, 2: cao');
            $table->text('note');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
