<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const donnees = @json($donnees);
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
                .filter(v => !isNaN(v));
            const sum = values.reduce((a, b) => a + b, 0);
            result.push(values.length > 0 ? sum / values.length : null);
        }

        return result;
    }

    function updateChart() {
        const checked = Array.from(document.querySelectorAll('input[type="checkbox"]:checked')).map(c => c.value);
        const minDate = dateMinInput?.value ? new Date(dateMinInput.value) : null;
        const maxDate = dateMaxInput?.value ? new Date(dateMaxInput.value) : null;

        const filtered = donnees
            .filter(d => {
                const dDate = new Date(d.date);
                if (minDate && dDate < minDate) return false;
                if (maxDate && dDate > maxDate) return false;
                return true;
            })
            .sort((a, b) => new Date(a.date) - new Date(b.date));

        const traces = [];

        ['poids', 'calories', 'depenses'].forEach(field => {
            if (document.getElementById('check-' + field).checked) {
                const x = filtered.map(d => d.date);
                const y = filtered.map(d => parseLocaleFloat(d[field]));
                const labels = filtered.map(d => d.etiquettes || '');

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
                        color: (field === 'calories') ? 'rgba(255,100,100,0.3)' :
                               (field === 'depenses') ? 'rgba(0,150,0,0.5)' :
                               undefined
                    },
                    yaxis: (field === 'poids') ? 'y' : 'y2'
                });

                if (field === 'calories') {
                    const yTrend = movingAverage(y, 8);
                    traces.push({
                        x: x,
                        y: yTrend,
                        name: 'Tendance Calories',
                        mode: 'lines',
                        type: 'scatter',
                        connectgaps: true,
                        line: { color: 'rgba(255,0,0,1)', width: 3 },
                        yaxis: 'y2'
                    });

                    traces.push({
                        x: x,
                        y: x.map(() => 2000),
                        name: 'Seuil 2000 kcal',
                        mode: 'lines',
                        type: 'scatter',
                        line: { color: 'black', width: 2, dash: 'dashdot' },
                        yaxis: 'y2',
                        hoverinfo: 'none',
                        showlegend: true
                    });
                }
            }
        });

        const layout = {
            title: 'Evolution des données',
            dragmode: 'zoom',
            margin: { t: 40 },
            yaxis: { title: 'Poids (kg)', side: 'left' },
            yaxis2: {
                title: 'Calories / Dépenses',
                overlaying: 'y',
                side: 'right',
                showgrid: false
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
