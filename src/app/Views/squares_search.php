<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>

<h2>Find A Square</h2>
<div class="alert alert-info" role="alert">
	PLEASE NOTE: this page is currently still under development and may not return accurate information.
</div>

<div class="form-group row">
	<label for="in" class="col-md-2 col-form-label d-none d-md-inline">Groups</label>
	<div class="col-md-10">
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="plants" value="scientific" <?= set_radio('groups', 'plants', true); ?> />
			<label class="form-check-label" for="scientific-name">
				only plants
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="bryophytes" value="axiophyte" <?= set_radio('groups', 'bryophytes'); ?> />
			<label class="form-check-label" for="axiophyte-name">
				only bryophytes
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="both" value="common" <?= set_radio('groups', 'both'); ?> />
			<label class="form-check-label" for="common-name">
				both plants and bryophytes
			</label>
		</div>
	</div>
</div>

<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>

<div id="map"></div>
<script>
	// Initialise the map
	const map = L.map("map", {
		zoomSnap: 0,
	}).setView([52.6354, -2.71975], 9);

	// Make a minimal base layer using Mapbox data
	const minimal = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		maxZoom: 18,
		id: "mapbox/outdoors-v11",
		tileSize: 512,
		zoomOffset: -1,
		accessToken: "pk.eyJ1Ijoiam9lamNvbGxpbnMiLCJhIjoiY2tnbnpjZmtpMGM2MTJ4czFqdHEzdmNhbSJ9.Fin7MSPizbCcQi6hSzVigw"
	});

// OS Grid graticules
	// 10km grid graticule shown between zoom levels 8 and 11 and has no axis labels
	const graticule10km = L.britishGrid({
		color: '#216fff',
		weight: 1,
		showAxisLabels: [],
		minInterval: 10000,
		maxInterval: 10000,
		minZoom: 8,
		maxZoom: 11
	});

	// 1km grid graticule shown between zoom levels 11 and 15 and has labelled axis
	const graticule1km = L.britishGrid({
		color: '#216fff',
		weight: 1,
		showAxisLabels: [1000],
		minInterval: 1000,
		maxInterval: 1000,
		minZoom: 11,
		maxZoom: 15
	});

	// Initialise geoJson boundary layer
	const boundary = L.geoJSON(null, {
		"color": "#0996DB",
		"weight": 5,
		"opacity": 0.33
	});

	// Create a Layer Group and add to map
	const layers = L.layerGroup([minimal,  graticule10km, graticule1km, boundary]);
	layers.addTo(map);

	// Load shropshire geojson and fit map to boundaries
	const url = "/data/shropshire_simple.json";
	fetch(url)
		.then((response) => response.json())
		.then((geojson) => {
			boundary.addData(geojson);
			map.fitBounds(boundary.getBounds(geojson).pad(0.1));
		});


	L.control.locate().addTo(map);

	var options = {};
	//L.osGraticule(options).addTo(map);


</script>

<?= $this->endSection() ?>
