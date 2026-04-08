<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seance_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seance_exercice_id')->constrained('seance_exercices')->cascadeOnDelete();
            $table->unsignedTinyInteger('numero');
            $table->boolean('effectuee')->default(false);
            $table->unsignedInteger('reps')->nullable();
            $table->decimal('poids', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seance_series');
    }
};
