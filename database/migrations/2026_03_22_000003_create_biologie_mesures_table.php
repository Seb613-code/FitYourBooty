<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biologie_mesures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analyse_id')->constrained('biologie_analyses')->cascadeOnDelete();
            $table->foreignId('parametre_id')->constrained('biologie_parametres')->cascadeOnDelete();
            $table->decimal('valeur', 12, 4)->nullable();
            $table->timestamps();
            $table->unique(['analyse_id', 'parametre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biologie_mesures');
    }
};
