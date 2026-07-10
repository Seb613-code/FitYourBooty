<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $fields = ['poids', 'pas', 'calories', 'proteines', 'lipides', 'glucides', 'depenses', 'etiquettes'];

        $duplicates = DB::table('donnees')
            ->select('user_id', 'date')
            ->groupBy('user_id', 'date')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::transaction(function () use ($duplicate, $fields) {
                $rows = DB::table('donnees')
                    ->where('user_id', $duplicate->user_id)
                    ->where('date', $duplicate->date)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $merged = [];

                foreach ($rows as $row) {
                    foreach ($fields as $field) {
                        if ($row->{$field} !== null && $row->{$field} !== '') {
                            $merged[$field] = $row->{$field};
                        }
                    }
                }

                $keep = $rows->last();

                DB::table('donnees')->where('id', $keep->id)->update($merged);
                DB::table('donnees')
                    ->whereIn('id', $rows->pluck('id')->filter(fn ($id) => $id !== $keep->id))
                    ->delete();
            });
        }

        Schema::table('donnees', function (Blueprint $table) {
            $table->unique(['user_id', 'date'], 'donnees_user_date_unique');
        });
    }

    public function down(): void
    {
        Schema::table('donnees', function (Blueprint $table) {
            $table->dropUnique('donnees_user_date_unique');
        });
    }
};
