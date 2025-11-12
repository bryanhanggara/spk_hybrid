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
        Schema::create('academic_questions', function (Blueprint $table) {
            $table->id();
            $table->enum('subject', ['mathematics', 'indonesian', 'english', 'science', 'social_studies']);
            $table->text('question');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->enum('correct_answer', ['a', 'b', 'c', 'd']);
            $table->integer('points')->default(1); // Bobot nilai per soal
            $table->integer('order')->default(0); // Urutan soal
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('subject');
            $table->index(['subject', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_questions');
    }
};

