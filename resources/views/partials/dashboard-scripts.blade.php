<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const donneesScript = document.getElementById('donnees-data');
    const donnees = JSON.parse(donneesScript?.textContent || '[]');
    const dateMinInput = document.getElementById("date-min");
    const dateMaxInput = document.getElementById("date-max");

    function parseLocaleFloat(value) {
        if (value === null || value === undefined || value === "") return null;
        if (typeof value === 'number') return value;
        return parseFloat(
            String(value)
                .trim()
                .replace(/\s/g, '')
                .replace(',', '.')
        );
    }

    function movingAverage(data, windowSize) {
        const result = [];
        const half = Math.floor(windowSize / 2);

        for (let i = 0; i < data.length; i++) {
            let start = Math.max(0, i - half);
            let end = Math.min(data.length, i + half + 1);
            const values = data.slice(start, end)
                .map(parseLocaleFloat)
                .filter(v => v !== null && v !== undefined && !isNaN(v));
            const sum = values.reduce((a, b) => a + b, 0);
            result.push(values.length > 0 ? sum / values.length : null);
        }

        return result;
    }

    function findLatestPoidsEntry(data) {
        const sorted = [...data]
            .filter(d => !isNaN(parseLocaleFloat(d.poids)))
            .sort((a, b) => new Date(a.date) - new Date(b.date));
        return sorted.length ? sorted[sorted.length - 1] : null;
    }

    function findPoidsAtOrBeforeDate(data, targetDate) {
        const target = new Date(targetDate);
        const sorted = [...data]
            .filter(d => !isNaN(parseLocaleFloat(d.poids)))
            .sort((a, b) => new Date(a.date) - new Date(b.date));

        for (let i = sorted.length - 1; i >= 0; i--) {
            if (new Date(sorted[i].date) <= target) {
                return parseLocaleFloat(sorted[i].poids);
            }
        }

        return null;
    }

    function updateChart() {
        const theme = {
            poids: '#7dd3fc',
            calories: 'rgba(251, 113, 133, 0.4)',
            depenses: '#34d399',
            caloriesTrend: '#f43f5e',
            seuil: '#facc15',
            objectif: '#60a5fa',
            text: '#e2e8f0',
            grid: 'rgba(148, 163, 184, 0.2)'
        };
        const checked = Array.from(document.querySelectorAll('input[type="checkbox"]:checked')).map(c => c.value);
        const minDate = dateMinInput?.value ? new Date(dateMinInput.value) : null;
        const maxDate = dateMaxInput?.value ? new Date(dateMaxInput.value) : null;
        const seuilInput = document.getElementById('seuil_calories');
        if (seuilInput && !seuilInput.value) {
            seuilInput.value = '2000';
        }
        const seuilCalories = seuilInput?.value ? parseFloat(seuilInput.value) : 2000;

        const filtered = donnees
            .filter(d => {
                const dDate = new Date(d.date);
                if (minDate && dDate < minDate) return false;
                if (maxDate && dDate > maxDate) return false;
                return true;
            })
            .sort((a, b) => new Date(a.date) - new Date(b.date));

        const traces = [];

        // Poids, Calories, Dépenses classiques
        ['poids', 'calories', 'depenses'].forEach(field => {
            if (document.getElementById('check-' + field)?.checked) {
                const x = filtered.map(d => d.date);
                const y = filtered.map(d => parseLocaleFloat(d[field]));
                const labels = filtered.map(d => d.etiquettes || '');

                const lineColor = (field === 'calories') ? theme.calories
                    : (field === 'depenses') ? theme.depenses
                    : theme.poids;

                traces.push({
                    x,
                    y,
                    text: labels,
                    textposition: 'top center',
                    hoverinfo: 'text+y+x',
                    name: field.charAt(0).toUpperCase() + field.slice(1),
                    mode: 'lines+markers+text',
                    type: 'scatter',
                    connectgaps: true,
                    line: {
                        color: lineColor,
                        width: 2.5
                    },
                    marker: {
                        color: lineColor,
                        size: 6
                    },
                    yaxis: (field === 'poids') ? 'y' : 'y2'

                });	


                // Ajout de la tendance Calories
                if (field === 'calories') {
                    const yTrend = movingAverage(y, 7);
                    traces.push({
                        x: x,
                        y: yTrend,
                        name: 'Tendance Calories',
                        mode: 'lines',
                        type: 'scatter',
                        connectgaps: true,
                        line: { color: theme.caloriesTrend, width: 3 },
                        yaxis: 'y2'
                    });
                    traces.push({
                        x: x,
                        y: x.map(() => seuilCalories),
                        name: 'Seuil ' + seuilCalories + ' kcal',
                        mode: 'lines',
                        type: 'scatter',
                        line: { color: theme.seuil, width: 2, dash: 'dashdot' },
                        yaxis: 'y2',
                        hoverinfo: 'none',
                        showlegend: true
                    });
				 
						
                }
                if (field === 'poids') {
                    const objectifStart = document.getElementById('objectif_start')?.value;
                    const objectifEnd = document.getElementById('objectif_end')?.value;
                    const objectifWeightValue = parseLocaleFloat(document.getElementById('objectif_weight')?.value);

                    if (objectifStart && objectifEnd && !isNaN(objectifWeightValue)) {
                        const startWeight = findPoidsAtOrBeforeDate(filtered, objectifStart);
                        if (!isNaN(startWeight)) {
                            traces.push({
                                x: [objectifStart, objectifEnd],
                                y: [startWeight, objectifWeightValue],
                                name: 'Objectif poids',
                                mode: 'lines+markers',
                                type: 'scatter',
                                line: { color: theme.objectif, width: 3, dash: 'dash' },
                                marker: { color: theme.objectif, size: 6 },
                                yaxis: 'y',
                                hoverinfo: 'y',
                                showlegend: true
                            });
                        }
                    }
                }
            }
        });

const layout = {
    title: 'Evolution des données',
    dragmode: 'zoom',
    margin: { t: 40, l: 50, r: 50, b: 40 },
    paper_bgcolor: 'rgba(0,0,0,0)',
    plot_bgcolor: 'rgba(0,0,0,0)',
    font: { color: theme.text },
    xaxis: {
        type: 'date',
        gridcolor: theme.grid,
        zerolinecolor: theme.grid
    },
    yaxis: {
        title: 'Poids (kg)',
        side: 'left',
        gridcolor: theme.grid,
        zerolinecolor: theme.grid
    },
    yaxis2: {
        title: 'Calories / Dépenses',
        overlaying: 'y',
        side: 'right',
        showgrid: false
    },
    legend: {
        orientation: 'h',
        y: -0.2
    }
};

        Plotly.newPlot('chart', traces, layout, { responsive: true });
    }

    document.querySelectorAll('input[type="checkbox"]').forEach(box => {
        box.addEventListener('change', updateChart);
    });

    if (dateMinInput && dateMaxInput) {
        dateMinInput.addEventListener('input', updateChart);
        dateMaxInput.addEventListener('input', updateChart);
    }

    const seuilInput = document.getElementById('seuil_calories');
    if (seuilInput) {
        if (!seuilInput.value) {
            seuilInput.value = '2000';
        }
        seuilInput.addEventListener('input', updateChart);
    }

    const objectifStartInput = document.getElementById('objectif_start');
    const objectifEndInput = document.getElementById('objectif_end');
    const objectifWeightInput = document.getElementById('objectif_weight');

    if (objectifEndInput && !objectifEndInput.value) {
        const storedEnd = localStorage.getItem('objectif_end');
        if (storedEnd) {
            objectifEndInput.value = storedEnd;
        }
    }

    if (objectifWeightInput && !objectifWeightInput.value) {
        const storedWeight = localStorage.getItem('objectif_weight');
        if (storedWeight) {
            objectifWeightInput.value = storedWeight;
        }
    }

    if (objectifStartInput && !objectifStartInput.value) {
        objectifStartInput.value = '2026-03-13';
    }

    window.appliquerObjectif = function () {
        const objectifStart = objectifStartInput?.value;
        const objectifEnd = objectifEndInput?.value;
        const objectifWeight = objectifWeightInput?.value;

        if (objectifEnd) {
            localStorage.setItem('objectif_end', objectifEnd);
        }
        if (objectifWeight) {
            localStorage.setItem('objectif_weight', objectifWeight);
        }
        if (!objectifStart && objectifStartInput) {
            objectifStartInput.value = '2026-03-13';
        }

        updateChart();
    };

    ['objectif_start', 'objectif_end', 'objectif_weight'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', () => {
                if (id === 'objectif_end') {
                    localStorage.setItem('objectif_end', input.value);
                }
                if (id === 'objectif_weight') {
                    localStorage.setItem('objectif_weight', input.value);
                }
                updateChart();
            });
        }
    });

    updateChart();

    const resetBtn = document.getElementById("reset-filtres");
    const rows = Array.from(document.querySelectorAll("#donnees-table tbody tr"));

    function filterRows() {
        const minDate = dateMinInput.value ? new Date(dateMinInput.value) : null;
        const maxDate = dateMaxInput.value ? new Date(dateMaxInput.value) : null;

        rows.forEach(row => {
            const dateCell = row.querySelector("td").textContent.trim();
            const rowDate = new Date(dateCell.split("-").reverse().join("-"));
            let visible = true;
            if (minDate && rowDate < minDate) visible = false;
            if (maxDate && rowDate > maxDate) visible = false;
            row.style.display = visible ? "" : "none";
        });
    }

    dateMinInput.addEventListener("input", filterRows);
    dateMaxInput.addEventListener("input", filterRows);
    resetBtn.addEventListener("click", function () {
        dateMinInput.value = "";
        dateMaxInput.value = "";
        rows.forEach(row => row.style.display = "");
        updateChart();
    });

    filterRows();
});
</script>
