<?php

it('uses the pinned lightweight Plotly bundle for the dashboard chart', function () {
    $script = file_get_contents(resource_path('views/partials/dashboard-scripts.blade.php'));

    expect($script)->toContain('https://cdn.plot.ly/plotly-basic-2.35.2.min.js')
        ->and($script)->toContain('defer');
});

it('updates the dashboard chart without recreating its Plotly instance', function () {
    $script = file_get_contents(resource_path('views/partials/dashboard-scripts.blade.php'));

    expect($script)->toContain("Plotly.react('chart', traces, layout")
        ->and($script)->not->toContain("Plotly.newPlot('chart'");
});

it('normalizes dashboard chart data once before chart updates', function () {
    $script = file_get_contents(resource_path('views/partials/dashboard-scripts.blade.php'));

    expect($script)->toContain('const chartData = donnees')
        ->and($script)->toContain('const filtered = chartData');
});
