<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('exercices')) {
            Schema::create('exercices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seance_type_id')->constrained('seance_types')->cascadeOnDelete();
                $table->string('nom');
                $table->timestamps();
            });

            return;
        }

        if (!Schema::hasColumn('exercices', 'seance_type_id')) {
            Schema::table('exercices', function (Blueprint $table) {
                $table->unsignedBigInteger('seance_type_id')->nullable()->after('id');
                $table->index('seance_type_id');
            });
        }

        if (Schema::hasColumn('exercices', 'type')) {
            $typeMap = DB::table('seance_types')->pluck('id', 'code');

            foreach ($typeMap as $code => $id) {
                DB::table('exercices')
                    ->where('type', $code)
                    ->update(['seance_type_id' => $id]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('exercices');
    }
};
