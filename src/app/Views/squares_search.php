<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<h2>Find A Square</h2>
<div class="form-group row">
    <label for="in" class="col-md-2 col-form-label d-none d-md-inline">Groups</label>
    <div class="col-md-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="species-group"  id="plants"
                value="scientific" <?php echo set_radio('groups', 'plants', TRUE); ?> />
            <label class="form-check-label" for="scientific-name">
                only plants
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="species-group"  id="bryophytes"
                value="axiophyte" <?php echo set_radio('groups', 'bryophytes'); ?> />
            <label class="form-check-label" for="axiophyte-name">
                only bryophytes
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="species-group" id="both"
                value="common" <?php echo set_radio('groups', 'both'); ?> />
            <label class="form-check-label" for="common-name">
                both plants and bryophytes
            </label>
        </div>
    </div>
</div>

<div id="mapid" style="height: 500px;"></div>
<script>


var mymap = L.map('mapid').setView([52.6, -3.0], 9);

var osmLayer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1Ijoiam9lamNvbGxpbnMiLCJhIjoiY2tnbnpjZmtpMGM2MTJ4czFqdHEzdmNhbSJ9.Fin7MSPizbCcQi6hSzVigw'
});


var baseMaps = {
  "OSM": osmLayer
};

osmLayer.addTo(mymap);

var options = {};
L.osGraticule(options).addTo(mymap);

const url = 'data/shropshire.json';

fetch(url)
.then(function(response) {
return response.json();
})
.then(function(data) {
L.geoJSON(data).addTo(mymap);
});
</script>

<?= $this->endSection() ?>