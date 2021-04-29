<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center">
	<a href="/species/<?=$speciesName ?>/group/both/type/scientific" class="header-backArrow">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
		</svg>
	</a>
	<h2>
		<?= urldecode($displayName) ?> records in
		Shropshire
	</h2>
</div>
<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>


<?php if (isset($download_link)) : ?>
	<p><a href="<?= $download_link ?>">Download this data</a></p>
<?php endif ?>

<ul id="tabs" class="nav nav-tabs d-lg-none" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#map-container" type="button" role="tab" aria-controls="map" aria-selected="true">Map</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="false">Data</button>
	</li>
</ul>
<p><?= $totalRecords ?> records</p>
<div id="tab-content" class="row">

	<div id="map-container" class="tab-pane fade show active col-lg">
		<div id="map" class=""></div>
	</div>

	<div id="data" class="tab-pane fade show col-lg">
		<?php if (isset($recordsList)) : ?>
			<table class="table">
				<thead>
					<tr>
						<th>Site</th>
						<th class="d-none d-sm-table-cell">Square</th>
						<th class="d-none d-md-table-cell">Collector</th>
						<th>Year</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($recordsList as $record) : ?>
						<tr data-uuid="<?= $record->uuid ?>">
							<td>
								<a href="/site/<?= $record->locationId ?>/species/<?=$speciesName ?>">
									<?= $record->locationId ?>
								</a>
							</td>
							<td class="d-none d-sm-table-cell">
								<a href="/square/<?= $record->gridReference ?>/species/<?=$speciesName ?>">
									<?= $record->gridReference ?>
								</a>
							</td>
							<td class="d-none d-md-table-cell">
								<?= $record->collector ?>
							</td>
							<td>
								<?= $record->year ?>
							</td>
							<td>
								<a href="<?= base_url("/record/{$record->uuid}") . '?displayName=' . $displayName; ?>">
									more
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif ?>
	</div>
</div>
<script>
	// Initialise the map
	const map = L.map("map", {
		zoomSnap: 0,
	});

	// Make a minimal base layer using Mapbox data
	const minimal = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		maxZoom: 18,
		id: "mapbox/outdoors-v11",
		tileSize: 512,
		zoomOffset: -1,
		accessToken: "pk.eyJ1Ijoiam9lamNvbGxpbnMiLCJhIjoiY2tnbnpjZmtpMGM2MTJ4czFqdHEzdmNhbSJ9.Fin7MSPizbCcQi6hSzVigw"
	});

	//OS Grid 10k graticule
	const graticule10km = L.osGraticule({
		interval: 10000,
		minZoom: 8,
		maxZoom: 11,
	});

	const graticule1km = L.osGraticule({
		interval: 1000,
		minZoom: 11,
		maxZoom: 15,
	});

	//make a dot map layer
	const wmsUrl = "https://records-ws.nbnatlas.org/mapping/wms/reflect?" +
		"Q=lsid:<?= $recordsList[0]->speciesGuid ?>" +
		"&ENV=colourmode:osgrid;color:ffff00;name:circle;size:4;opacity:0.5;" +
		"gridlabels:true;gridres:singlegrid" +
		"&fq=data_resource_uid:dr782";

	const species = L.tileLayer.wms(wmsUrl, {
		"layers": "ALA:occurrences",
		"uppercase": true,
		"format": "image/png",
		"transparent": true
	});

	// Initialise geoJson boundary layer
	const boundary = L.geoJSON(null, {
		"color": "#0996DB",
		"weight": 5,
		"opacity": 0.33
	});

	// Create a Layer Group and add to map
	const layers = L.layerGroup([minimal, graticule10km, graticule1km, boundary, species]);
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

	// Plot page of records on the map with tooltips
	const records = <?= json_encode($recordsList) ?>;

	const recordMarkers = records.map(record => {
		const lat = record.decimalLatitude;
		const lng = record.decimalLongitude;

		const marker = L.circleMarker([lat, lng], {
			fillColor: "red",
			color: "darkRed",
			fillOpacity: .75
		});

		marker.uuid = record.uuid;

		marker.bindPopup(`
			${record.locationId} (${record.gridReference})<br>
			${record.collector}<br>
		`);

		// Events for hovering over markers
		// marker.on("mouseover", (event) => {
		// 	const highlightRow = document.querySelector(`[data-uuid="${event.target.uuid}"]`);
		// 	highlightRow.style.backgroundColor = 'rgb(255, 255, 0, 0.5)';
		// });

		// marker.on("mouseout", (event) => {
		// 	const highlightRow = document.querySelector(`[data-uuid="${event.target.uuid}"]`);
		// 	highlightRow.style.backgroundColor = 'initial';
		// })

		// When we open a popup also highlight the corresponding row in the data
		// table. This is _essential_ for orientation when using tabs on small
		// screens
		marker.on("popupopen", (event) => {
			const highlightRow = document.querySelector(`[data-uuid="${event.target.uuid}"]`);
			highlightRow.style.backgroundColor = 'rgb(255, 255, 0, 0.5)';
		});

		marker.on("popupclose", (event) => {
			const highlightRow = document.querySelector(`[data-uuid="${event.target.uuid}"]`);
			highlightRow.style.backgroundColor = 'initial';
		})

		return marker;
	});
	L.layerGroup(recordMarkers).addTo(map);

	// Plot the sites to the map as markers
	const sites = <?= json_encode($sites) ?>;
	const siteMarkers = Object.entries(sites).map(site => {
		return L.circleMarker(site[1]).bindTooltip(site[0]);
	});
	// Turn off rendering of sites for now
	// L.layerGroup([...siteMarkers]).addTo(map);

	// When the page loads or on resize, we check whether we are on small screen
	// or large. If on large - >= 992px - we remove the tabs; if on small, we
	// them again.
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
<nav>
	<ul class="pagination justify-content-center">
		<?php if ($page > 1) : ?>
			<li class="page-item"><a class="page-link" href="<?= current_url() . '?page=' . ($page - 1) ?>">Previous</a></li>
		<?php endif ?>
		<?php if (count($recordsList) === 10) : ?>
			<li class="page-item"><a class="page-link" href="<?= current_url() . '?page=' . ($page + 1) ?>">Next</a></li>
		<?php endif ?>
	</ul>
</nav>

<?php if (isset($download_link)) : ?>
	<p><a href="<?= $download_link ?>">Download this data</a></p>
<?php endif ?>

<?= $this->endSection() ?>
