<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biologie_parametres', function (Blueprint $table) {
            $table->unsignedSmallInteger('sort_order')->nullable()->after('label');
        });
    }

    public function down(): void
    {
        Schema::table('biologie_parametres', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
