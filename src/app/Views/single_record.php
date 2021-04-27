<?= $this->extend('default') ?>
<?= $this->section('content') ?>

<h2>Single Record</h2>

<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>


<div id="mapid"></div>
<?= $location->gridReferenceWKT ?>
<script>
	var mymap = L.map("mapid").setView([53.0, -3.0], 13);

	var osmLayer = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		maxZoom: 18,
		id: "mapbox/streets-v11",
		tileSize: 512,
		zoomOffset: -1,
		accessToken: "pk.eyJ1Ijoiam9lamNvbGxpbnMiLCJhIjoiY2tnbnpjZmtpMGM2MTJ4czFqdHEzdmNhbSJ9.Fin7MSPizbCcQi6hSzVigw"
	});

	var britishGrid = L.britishGrid({
		color: "#00f",
		showSquareLabels: [100000], // label 100km grid squares
		drawClip: true
	});

	var baseMaps = {
		"OSM": osmLayer
	};

	var overlayMaps = {
		"British Grid": britishGrid
	};

	//L.control.layers(baseMaps, overlayMaps).addTo(mymap);
	osmLayer.addTo(mymap);

	var options = {};
	L.osGraticule(options).addTo(mymap);
</script>

<table class="table table-sm">
	<thead>
		<tr>
			<th scope="col">Record Id</th>
			<th scope="col"">{{record id ]]}}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td scope="row">Site Name</td>
			<td scope="row"><?= $location->locationID ?></td>
		</tr>
		<tr>
			<td scope="row">Full Grid Reference</td>
			<td scope="row"><?= $location->gridReference ?></td>
		</tr>
		<tr>
			<td scope="row">Full Grid Reference</td>
			<td scope="row"><?= $location->gridReference ?></td>
		</tr>
		<tr>
			<td scope="row">Recorders</td>
			<td scope="row"><?= $occurrence->recordedBy ?></td>
		</tr>
		<tr>
			<td scope="row">Full Date</td>
			<td scope="row"><?= $event->eventDate ?></td>
		</tr>
		<tr>
			<td scope="row">Year</td>
			<td scope="row"><?= $event->year ?></td>
		</tr>
		<tr>
			<td scope="row">Phylum</td>
			<td scope="row"><?= $classification->phylum ?></td>
		</tr>
		<tr>
			<td scope="row">Scientific Name</td>
			<td scope="row"><?= $classification->scientificName ?></td>
		</tr>
		<tr>
			<td scope="row">Common Name</td>
			<td scope="row"><?= $classification->vernacularName ?></td>
		</tr>
	</tbody>
</table>

<?= $this->endSection() ?>
