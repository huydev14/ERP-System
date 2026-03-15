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
        Schema::create('arranges', function (Blueprint $table) {
            $table->id();

            $table->date('day')->nullable();
            $table->string('name_arrange')->nullable();
            $table->string('name_customer')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_customer')->nullable();
            $table->string('name')->nullable();

            $table->foreignId('sale_user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('teams')->cascadeOnUpdate()->nullOnDelete();

            $table->string('account_social')->nullable();

            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('support_user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();

            $table->unsignedTinyInteger('arrange_type')->default(0)->comment('0: mới, 1: cũ');
            $table->unsignedTinyInteger('result')->default(0)->comment('0: chưa bốc, 1: hoàn thành, 2: fail');
            $table->text('fail_reason')->nullable();
            $table->integer('total_arrange')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arranges');
    }
};
