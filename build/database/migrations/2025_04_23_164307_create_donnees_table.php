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
    Schema::create('donnees', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->float('poids')->nullable();
        $table->integer('calories')->nullable();
        $table->float('proteines')->nullable();
        $table->float('lipides')->nullable();
        $table->float('glucides')->nullable();
        $table->decimal('depenses', 8, 2)->nullable();
        $table->string('etiquettes')->nullable(); // champ texte libre (tags)
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donnees');
    }
};
