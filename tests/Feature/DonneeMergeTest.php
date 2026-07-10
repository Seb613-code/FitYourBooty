<?php

use App\Models\Donnee;
use App\Models\User;
use Illuminate\Http\UploadedFile;

it('fusionne les saisies partielles d une meme journee sans ecraser les valeurs existantes par du vide', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('donnees.store'), [
            'date' => '2025-12-31',
            'poids' => 72.4,
            'pas' => null,
            'calories' => null,
            'proteines' => null,
            'lipides' => null,
            'glucides' => null,
            'depenses' => null,
            'etiquettes' => null,
        ])
        ->assertRedirect(route('dashboard'));

    $this->actingAs($user)
        ->post(route('donnees.store'), [
            'date' => '2025-12-31',
            'poids' => null,
            'pas' => 8500,
            'calories' => null,
            'proteines' => null,
            'lipides' => null,
            'glucides' => null,
            'depenses' => null,
            'etiquettes' => null,
        ])
        ->assertRedirect(route('dashboard'));

    expect(Donnee::where('user_id', $user->id)->whereDate('date', '2025-12-31')->count())->toBe(1);

    $this->assertDatabaseHas('donnees', [
        'user_id' => $user->id,
        'date' => '2025-12-31',
        'poids' => 72.4,
        'pas' => 8500,
    ]);
});

it('dedoublonne les donnees historiques avec la derniere valeur non vide et impose une date unique', function () {
    $user = User::factory()->create();
    $migration = require database_path('migrations/2026_07_10_160100_merge_duplicate_donnees_and_add_unique_user_date.php');

    $migration->down();

    Donnee::create(['user_id' => $user->id, 'date' => '2025-12-31', 'poids' => 72.4, 'pas' => 8000]);
    Donnee::create(['user_id' => $user->id, 'date' => '2025-12-31', 'poids' => 72.1, 'pas' => null, 'calories' => 2100]);

    $migration->up();

    expect(Donnee::where('user_id', $user->id)->whereDate('date', '2025-12-31')->count())->toBe(1);

    $this->assertDatabaseHas('donnees', [
        'user_id' => $user->id,
        'date' => '2025-12-31',
        'poids' => 72.1,
        'pas' => 8000,
        'calories' => 2100,
    ]);
});

it('fusionne les lignes CSV de meme date et conserve la derniere valeur non vide', function () {
    $user = User::factory()->create();

    Donnee::create([
        'user_id' => $user->id,
        'date' => '2025-12-31',
        'poids' => 72.4,
    ]);

    $csv = "date,poids,pas,calories,proteines,lipides,glucides,depenses,etiquettes\n"
        .'31-12-2025,,8500,,,,,,\n';

    $this->actingAs($user)
        ->post(route('donnees.import.csv'), [
            'csv' => UploadedFile::fake()->createWithContent('donnees.csv', $csv),
        ])
        ->assertRedirect();

    expect(Donnee::where('user_id', $user->id)->whereDate('date', '2025-12-31')->count())->toBe(1);

    $this->assertDatabaseHas('donnees', [
        'user_id' => $user->id,
        'date' => '2025-12-31',
        'poids' => 72.4,
        'pas' => 8500,
    ]);
});
