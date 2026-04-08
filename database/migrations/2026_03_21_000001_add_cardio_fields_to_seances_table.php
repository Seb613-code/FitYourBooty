<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->unsignedInteger('duration_minutes')->nullable()->after('date');
            $table->unsignedInteger('calories')->nullable()->after('duration_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('seances', function (Blueprint $table) {
            $table->dropColumn(['duration_minutes', 'calories']);
        });
    }
};
