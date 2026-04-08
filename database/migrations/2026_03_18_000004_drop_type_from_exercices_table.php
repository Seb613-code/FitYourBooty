<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('exercices', 'type')) {
            Schema::table('exercices', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('exercices', 'type')) {
            Schema::table('exercices', function (Blueprint $table) {
                $table->string('type')->nullable()->after('nom');
            });
        }
    }
};
