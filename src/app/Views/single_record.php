<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center">
	<?php if ($status === 'OK') : ?>
		<a href="/species/<?= $classification->scientificName ?>?displayName=<?= $displayName ?>" class="header-backArrow">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
				<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
			</svg>
		</a>
	<?php endif ?>

	<h2><?= $title ?></h2>
</div>

<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>: <?= $message ?>">
	</div>
<?php endif ?>

<?php if ($status === 'OK') : ?>

	<ul id="tabs" class="nav nav-tabs d-lg-none" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="map-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="true">Data</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="data-tab" data-bs-toggle="tab" data-bs-target="#map-container" type="button" role="tab" aria-controls="map" aria-selected="false">Map</button>
		</li>
	</ul>

	<div id="tab-content" class="row">
		<div id="data" class="tab-pane fade show active col-lg">
			<table class="table table-sm">
				<thead>
					<tr>
						<th scope="col">Record Id</th>
						<th scope="col"><?= $recordId ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td scope=" row">Site Name</td>
						<td scope="row"><a href="/site/<?= $location->locationID ?>/species/<?= $classification->scientificName ?>"><?= $location->locationID ?></a></td>
					</tr>
					<tr>
						<td scope="row">Full Grid Reference</a></td>
						<td scope="row"><a href="/square/<?= $location->gridReference ?>/species/<?= $classification->scientificName ?>"><?= $location->gridReference ?></a></td>
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
		</div>
		<div id="map-container" class="tab-pane fade show col-lg">
			<div id="map" class=""></div>
		</div>
	</div>

	<script>
		// Initialise the map
		const map = L.map("map", {
			zoomSnap: 0,
		}).setView([52.6354, -2.71975], 13);


		// Make a minimal base layer using Mapbox data
		const minimal = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
			maxZoom: 18,
			id: "mapbox/outdoors-v11",
			tileSize: 512,
			zoomOffset: -1,
			accessToken: "pk.eyJ1IjoiY2hhcmxlc3JvcGVyIiwiYSI6ImNrbmY2YXl4ZTJjbDQydm1xOW83MXh1eDIifQ.ntclZm-a8OxwUEBODW08FQ"
		});

		// Initialise geoJson boundary layer
		const boundary = L.geoJSON(null, {
			"color": "#0996DB",
			"weight": 5,
			"opacity": 0.33
		});

		// Create a Layer Group and add to map
		const layers = L.layerGroup([minimal, boundary]);
		layers.addTo(map);



		// We load the geojson data from disk using the JavaScript Fetch API. When
		// the response resolves, we add the data to the boundary layer and use the
		// fitBounds() Leaflet method to zoom and position the map around the
		// boundary data with a touch of padding.
		const url = "/data/shropshire_simple.json";
		fetch(url)
			.then((response) => response.json())
			.then((geojson) => {
				boundary.addData(geojson);
				map.fitBounds(boundary.getBounds(geojson).pad(0.1));
			});


		["load", "resize"].forEach((event) => {
			window.addEventListener(event, () => {
				const activeTab = document.querySelector("[aria-selected='true']");
				new bootstrap.Tab(activeTab).show();

				if (window.matchMedia("(min-width: 992px)").matches) {
					document.querySelector("#tab-content").classList.remove("tab-content");
					document.querySelector("#map-container").classList.add("show");
					document.querySelector("#data").classList.add("show");
				} else {
					document.querySelector("#tab-content").classList.add("tab-content");
					bootstrap.Tab.getInstance(activeTab).show();
				}
			});
		});
	</script>
<?php endif ?>
<?= $this->endSection() ?>
