<div class="card shadow-sm">
    <div class="card-header bg-info text-white">Graphique</div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Données à afficher :</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="check-poids" value="poids" checked>
                <label class="form-check-label" for="check-poids">Poids</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="check-calories" value="calories" checked>
                <label class="form-check-label" for="check-calories">Calories</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="check-depenses" value="depenses" checked>
                <label class="form-check-label" for="check-depenses">Dépenses</label>
            </div>
        </div>
        <div id="chart" style="height: 400px;"></div>
    </div>
</div>
