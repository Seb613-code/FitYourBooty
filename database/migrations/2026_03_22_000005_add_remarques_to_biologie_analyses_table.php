<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biologie_analyses', function (Blueprint $table) {
            $table->text('remarques')->nullable()->after('date');
        });
    }

    public function down(): void
    {
        Schema::table('biologie_analyses', function (Blueprint $table) {
            $table->dropColumn('remarques');
        });
    }
};
