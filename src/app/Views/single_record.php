<?= $this->extend('default') ?>
<?= $this->section('content') ?>

<h2>Single Record</h2>

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


<?php
$table = new \CodeIgniter\View\Table();
$data  = [
	[
		'Item',
		'Value',
	],
	[
		'Site',
		$location->locationID,
	],
	[
		'Date',
		$event->eventDate,
	],
	[
		'Grid Reference',
		$location->gridReference,
	],
	[
		'Recorder',
		$occurrence->recordedBy,
	],
	[
		'Phylum',
		$classification->phylum,
	],
	[
		'Scientific Name',
		$classification->scientificName,
	],
	[
		'Common Name',
		$classification->vernacularName,
	],
];
?>

<?= $table->generate($data) ?>

<?= $this->endSection() ?>
