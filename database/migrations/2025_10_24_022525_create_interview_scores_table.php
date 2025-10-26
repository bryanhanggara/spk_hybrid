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
        Schema::create('interview_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('communication_skill'); // 1-5
            $table->integer('motivation'); // 1-5
            $table->integer('personality'); // 1-5
            $table->integer('academic_potential'); // 1-5
            $table->integer('career_orientation'); // 1-5
            $table->decimal('total_score', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_scores');
    }
};
