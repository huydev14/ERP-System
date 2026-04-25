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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // User Credentials
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken()->nullable();

            // Organization structure
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('positions')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained('teams')->cascadeOnUpdate()->nullOnDelete();

            // Employment details
            $table->unsignedTinyInteger('employment_type')->default(0)->comment('O: full-time, 1: part-time');
            $table->unsignedTinyInteger('status')->default(0)->comment('O: đang làm, 1: nghỉ việc');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            // Personal info
            $table->unsignedTinyInteger('gender')->default(0)->comment('O: nam, 1: nữ');
            $table->date('birthday')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
