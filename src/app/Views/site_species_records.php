<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center">
	<a href="javascript:history.back()" class="header-backArrow">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
		</svg>
	</a>
	<h2>
		<?= urldecode($speciesName) ?> records in
		<?= urldecode($siteName) ?>
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
		<button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="true">Data</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#map-container" type="button" role="tab" aria-controls="map" aria-selected="false">Map</button>
	</li>
</ul>
<div id="tab-content" class="row">
	<div id="data" class="tab-pane fade show active col-lg">
		<?php if (isset($recordsList)) : ?>
			<table class="table">
				<thead>
					<tr>
						<th class="d-none d-sm-table-cell">Square</th>
						<th class="d-none d-md-table-cell">Collector</th>
						<th>Year</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($recordsList as $record) : ?>
						<tr data-uuid="<?= $record->uuid ?>">
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
	<div id="map-container" class="tab-pane fade show col-lg">
			<div id="map" class=""></div>
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
			color: "#0996DB",
			weight: 5,
			opacity: 0.33
		});

		// Create a Layer Group and add to map
		const layers = L.layerGroup([minimal, boundary]);

		// Unless the first occurrence didn't contain a site location, create a
		// marker for the site's location
		<?php if (!empty($siteLocation)) : ?>
		const siteMarker = L.marker(<?= json_encode($siteLocation) ?>, {
			opacity: 0.75
		});
		layers.addLayer(siteMarker);
		<?php endif ?>

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

</div>
<?= $this->include('pagination') ?>

<?php if (isset($download_link)) : ?>
	<p><a href="<?= $download_link ?>">Download this data</a></p>
<?php endif ?>

<?= $this->endSection() ?>
