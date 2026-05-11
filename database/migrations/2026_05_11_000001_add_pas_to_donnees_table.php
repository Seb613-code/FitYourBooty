<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donnees', function (Blueprint $table) {
            $table->unsignedInteger('pas')->nullable()->after('poids');
        });
    }

    public function down(): void
    {
        Schema::table('donnees', function (Blueprint $table) {
            $table->dropColumn('pas');
        });
    }
};
