<?= $this->extend('default') ?>
<?= $this->section('content') ?>


<?php if (isset($message)) : ?>
<div class="alert alert-danger" role="alert">
	I am very sorry, but an error has occured.</b>:  <?= $message ?>
</div>
<?php endif ?>

<?php if ($status ==='OK') : ?>

<div class="d-flex align-items-center">
	<a href="javascript:history.back()" class="header-backArrow">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
		</svg>
	</a>
	<h2>
		<?= urldecode($speciesName) ?> records in
		<?= urldecode($gridSquare) ?>
	</h2>
</div>



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
<div id="tab-content" class="row">
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
								<a href="<?= base_url(" /record/{$record->uuid}"); ?>">
									more
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif ?>
	</div>
	<div id="map-container" class="tab-pane fade show active col-lg">
		<div id="map" class=""></div>
	</div>
</div>
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

	const wmsUrl = "https://records-ws.nbnatlas.org/mapping/wms/reflect?" +
		"Q=<?= $speciesName ?>" +
		"&ENV=colourmode:osgrid;gridres:singlegrid;color:ff0000;size:4;opacity:0.5" +
		"&fq=data_resource_uid:dr782%20AND%20grid_ref_1000:<?= $gridSquare ?>";

	const gridSquareTile = L.tileLayer.wms(wmsUrl, {
		"layers": "ALA:occurrences",
		"uppercase": true,
		"format": "image/png",
		"transparent": true
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
	const layers = L.layerGroup([minimal, gridSquareTile, graticule10km, graticule1km, boundary]);
	layers.addTo(map);

	// Load shropshire geojson and fit map to boundaries
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

<?= $this->include('pagination') ?>

	<?php if (isset($download_link)) : ?>
<p><a href="<?= $download_link ?>">Download this data</a></p>
	<?php endif ?>

<?php endif ?>

<?= $this->endSection() ?>
