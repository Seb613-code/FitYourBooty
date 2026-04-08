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
    Schema::table('donnees', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable(); // 👈 TEMPORAIREMENT NULL
    });

    // On met à jour les anciennes lignes si besoin (par exemple à user_id = 1)
    DB::table('donnees')->update(['user_id' => 1]);

    Schema::table('donnees', function (Blueprint $table) {
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


public function down(): void
{
    Schema::table('donnees', function (Blueprint $table) {
        $table->dropColumn('user_id');
    });
}

};
