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
        Schema::create('spk_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('recommended_major'); // IPA atau IPS
            $table->decimal('saw_score', 5, 4); // Skor SAW (0-1)
            $table->decimal('vikor_score', 5, 4); // Skor VIKOR (0-1)
            $table->decimal('final_score', 5, 4); // Skor akhir
            $table->integer('rank'); // Peringkat
            $table->json('calculation_details'); // Detail perhitungan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_results');
    }
};
