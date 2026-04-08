<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BiologieParametre;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $now = now();

        BiologieParametre::upsert([
            ['key' => 'hematies', 'label' => 'Hematies', 'sort_order' => 1, 'ref_min' => 4500000, 'ref_max' => 6500000],
            ['key' => 'hemoglobine_gdl', 'label' => 'Hemoglobine (g/dL)', 'sort_order' => 2, 'ref_min' => 13, 'ref_max' => 17],
            ['key' => 'hematocrite', 'label' => 'Hematocrite (%)', 'sort_order' => 3, 'ref_min' => 40, 'ref_max' => 54],
            ['key' => 'vgm', 'label' => 'VGM', 'sort_order' => 4, 'ref_min' => 80, 'ref_max' => 100],
            ['key' => 'tcmh', 'label' => 'TCMH', 'sort_order' => 5, 'ref_min' => 27, 'ref_max' => 32],
            ['key' => 'ccmh', 'label' => 'CCMH', 'sort_order' => 6, 'ref_min' => 30, 'ref_max' => 35],
            ['key' => 'leucocytes', 'label' => 'Leucocytes', 'sort_order' => 7, 'ref_min' => 4000, 'ref_max' => 10000],
            ['key' => 'pn_neutrophiles', 'label' => 'PN neutrophiles', 'sort_order' => 8, 'ref_min' => 1800, 'ref_max' => 7500],
            ['key' => 'pn_eosinophiles', 'label' => 'PN eosinophiles', 'sort_order' => 9, 'ref_min' => 0, 'ref_max' => 800],
            ['key' => 'pn_basophiles', 'label' => 'PN basophiles', 'sort_order' => 10, 'ref_min' => 0, 'ref_max' => 200],
            ['key' => 'lymphocytes', 'label' => 'Lymphocytes', 'sort_order' => 11, 'ref_min' => 1000, 'ref_max' => 4000],
            ['key' => 'monocytes', 'label' => 'Monocytes', 'sort_order' => 12, 'ref_min' => 200, 'ref_max' => 1000],
            ['key' => 'plaquettes', 'label' => 'Plaquettes', 'sort_order' => 13, 'ref_min' => 150000, 'ref_max' => 500000],
            ['key' => 'vpm', 'label' => 'VPM', 'sort_order' => 14, 'ref_min' => 0, 'ref_max' => 12],
            ['key' => 'glycemie_jeun_gl', 'label' => 'Glycemie a jeun (g/L)', 'sort_order' => 15, 'ref_min' => 0.74, 'ref_max' => 1.06],
            ['key' => 'glycemie_jeun_mmol', 'label' => 'Glycemie a jeun (mmol/L)', 'sort_order' => 16, 'ref_min' => 4.1, 'ref_max' => 5.9],
            ['key' => 'asat_tgo', 'label' => 'ASAT (TGO)', 'sort_order' => 17, 'ref_min' => 13, 'ref_max' => 40],
            ['key' => 'alat_tgp', 'label' => 'ALAT (TGP)', 'sort_order' => 18, 'ref_min' => 7, 'ref_max' => 40],
            ['key' => 'fib4', 'label' => 'FIB-4', 'sort_order' => 19, 'ref_min' => 0, 'ref_max' => 1.3],
            ['key' => 'gamma_gt', 'label' => 'Gamma GT', 'sort_order' => 20, 'ref_min' => 0, 'ref_max' => 55],
            ['key' => 'phosphatases_alcalines', 'label' => 'Phosphatases alcalines', 'sort_order' => 21, 'ref_min' => 30, 'ref_max' => 120],
            ['key' => 'lipase', 'label' => 'Lipase', 'sort_order' => 22, 'ref_min' => 0, 'ref_max' => 67],
            ['key' => 'creatinine_mgl', 'label' => 'Creatinine (mg/L)', 'sort_order' => 23, 'ref_min' => 6, 'ref_max' => 11],
            ['key' => 'creatinine_umol', 'label' => 'Creatinine (umol/L)', 'sort_order' => 24, 'ref_min' => 53, 'ref_max' => 97],
            ['key' => 'dfg_ckd_epi', 'label' => 'DFG CKD-EPI', 'sort_order' => 25, 'ref_min' => 90, 'ref_max' => null],
            ['key' => 'triglycerides_gl', 'label' => 'Triglycerides (g/L)', 'sort_order' => 26, 'ref_min' => 0.3, 'ref_max' => 1.5],
            ['key' => 'triglycerides_mmol', 'label' => 'Triglycerides (mmol/L)', 'sort_order' => 27, 'ref_min' => 0.46, 'ref_max' => 1.7],
            ['key' => 'chol_total_gl', 'label' => 'Cholesterol total (g/L)', 'sort_order' => 28, 'ref_min' => null, 'ref_max' => null],
            ['key' => 'ldl_chol_gl', 'label' => 'LDL cholesterol (g/L)', 'sort_order' => 29, 'ref_min' => null, 'ref_max' => null],
            ['key' => 'hdl_chol_gl', 'label' => 'HDL cholesterol (g/L)', 'sort_order' => 30, 'ref_min' => 0.4, 'ref_max' => 0.6],
            ['key' => 'sodium', 'label' => 'Sodium', 'sort_order' => 31, 'ref_min' => 136, 'ref_max' => 145],
            ['key' => 'potassium', 'label' => 'Potassium', 'sort_order' => 32, 'ref_min' => 3.5, 'ref_max' => 5.1],
            ['key' => 'osmolalite', 'label' => 'Osmolalite', 'sort_order' => 33, 'ref_min' => 280, 'ref_max' => 300],
            ['key' => 'chlore', 'label' => 'Chlore', 'sort_order' => 34, 'ref_min' => 101, 'ref_max' => 109],
            ['key' => 'ferritine', 'label' => 'Ferritine', 'sort_order' => 35, 'ref_min' => 20, 'ref_max' => 250],
            ['key' => 'crp', 'label' => 'CRP', 'sort_order' => 36, 'ref_min' => 0, 'ref_max' => 10],
            ['key' => 'tsh', 'label' => 'TSH', 'sort_order' => 37, 'ref_min' => 0.55, 'ref_max' => 4.78],
        ], ['key'], ['label', 'sort_order', 'ref_min', 'ref_max']);

        $musculation = \App\Models\SeanceCategorie::create(['nom' => 'Musculation']);
        $cardio = \App\Models\SeanceCategorie::create(['nom' => 'Cardio']);

        $pull = \App\Models\SeanceType::create([
            'categorie_id' => $musculation->id,
            'nom' => 'Pull',
            'code' => 'musculation_pull',
        ]);
        $push = \App\Models\SeanceType::create([
            'categorie_id' => $musculation->id,
            'nom' => 'Push',
            'code' => 'musculation_push',
        ]);
        \App\Models\SeanceType::create([
            'categorie_id' => $musculation->id,
            'nom' => 'Legs',
            'code' => 'musculation_legs',
        ]);
        \App\Models\SeanceType::create([
            'categorie_id' => $musculation->id,
            'nom' => 'Divers',
            'code' => 'musculation_divers',
        ]);
        \App\Models\SeanceType::create([
            'categorie_id' => $cardio->id,
            'nom' => 'Tapis',
            'code' => 'cardio_tapis',
        ]);
        \App\Models\SeanceType::create([
            'categorie_id' => $cardio->id,
            'nom' => 'Marche',
            'code' => 'cardio_marche',
        ]);
        \App\Models\SeanceType::create([
            'categorie_id' => $cardio->id,
            'nom' => 'Vélo',
            'code' => 'cardio_velo',
        ]);
        \App\Models\SeanceType::create([
            'categorie_id' => $cardio->id,
            'nom' => 'Autre',
            'code' => 'cardio_autre',
        ]);

        \App\Models\Exercice::insert([
            ['nom' => 'Tirage vertical', 'seance_type_id' => $pull->id, 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'Tirage horizontal', 'seance_type_id' => $pull->id, 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'Pec fly', 'seance_type_id' => $push->id, 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'Pec poulies haute', 'seance_type_id' => $push->id, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
