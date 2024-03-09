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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('level');
            $table->json('questions');
            $table->unsignedInteger('duration');
            $table->boolean('is_opened')->default(false);
            $table->json('answers')->nullable();
            $table->decimal('grade', 8, 2)->nullable();
            $table->timestamps();
            $table->timestamp('submited_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
