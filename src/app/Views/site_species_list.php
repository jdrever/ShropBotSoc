<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>

<div class="d-flex align-items-center">
	<a href="/sites/<?=$siteNameSearch ?>" class="header-backArrow">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
		</svg>
	</a>
	<h2>
		Species in <?= $siteName ?>
	</h2>
</div>

<div class="alert alert-info" role="alert">
	PLEASE NOTE: this page is currently still under development and may not return accurate information.
</div>

<?= form_open('species', '', array('site-name'=>$siteName)) ?>
<div class="row justify-content-center gy-3">
	<div class="form-group col-sm-4 col-lg-3">
		<div class="form-check">
			<input class="form-check-input" type="radio" name="name-type" id="scientific-name" value="scientific" onchange="this.form.submit();" <?= set_radio('name-type', 'scientific', ($nameType === 'scientific')); ?> />
			<label class="form-check-label" for="scientific-name">scientific<span class="d-none d-lg-inline"> name</span></label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="name-type" id="common-name" value="common"  onchange="this.form.submit();" <?= set_radio('name-type', 'common', ($nameType === 'common')); ?> />
			<label class="form-check-label" for="common-name">common<span class="d-none d-lg-inline"> name only</span></label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="name-type" id="axiophyte-name" value="axiophyte"  onchange="this.form.submit();" <?= set_radio('name-type', 'axiophyte'); ?> disabled />
			<label class="form-check-label" for="axiophyte-name">
				<span class="d-lg-none">axiophyte names</span>
				<span class="d-none d-lg-inline">axiophyte scientific name only</span>
			</label>
		</div>
	</div>
	<div class="form-group col-sm-4 col-lg-3">
		<div class="form-check">
			<input class="form-check-input" type="radio" name="species-group" id="plants" value="plants"  onchange="this.form.submit();" <?= set_radio('groups', 'plants', $speciesGroup === 'plants'); ?> />
			<label class="form-check-label" for="plants">only plants</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="species-group" id="bryophytes" value="bryophytes"  onchange="this.form.submit();" <?= set_radio('groups', 'bryophytes', $speciesGroup === 'bryophytes'); ?> />
			<label class="form-check-label" for="bryophytes">only bryophytes</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="species-group" id="both" value="both"  onchange="this.form.submit();" <?= set_radio('groups', 'both', $speciesGroup === 'both'); ?> />
			<label class="form-check-label" for="both">both <span class="d-none d-xl-inline">plants and bryophytes</span></label>
		</div>
	</div>
</div>
<?= form_close() ?>

<!-- Display search results and map showing site location-->
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
		<?php if (isset($speciesList)) : ?>
			<table class="table">
				<thead><tr>
					<th class="d-none d-md-table-cell">Family</th>
					<th>Scientific Name</th>
					<th class="d-none d-sm-table-cell">Common Name</th>
					<th>Count</th>
					<th>Records</th>
				</tr></thead>
				<tbody>
					<?php foreach ($speciesList as $species) : ?>
						<?php $speciesArray = explode('|', (string)$species->label); ?>
						<tr>
							<?php if ($nameType === 'scientific') : ?>
								<td class="d-none d-md-table-cell"><?php echo $speciesArray[4]?></td>
								<td><?=$speciesArray[0]?></td>
								<td class="d-none d-sm-table-cell"><?php echo $speciesArray[2]?></td>
								<td><?=$species->count?></td>
								<td><a href="/site/<?php echo $title ?>/species/<?=$speciesArray[0]?>">see records</a></td>
							<?php endif?>
							<?php if ($nameType === 'common') : ?>
								<td class="d-none d-md-table-cell"><?php echo $speciesArray[5]?></td>
								<td><?=$speciesArray[1]?></td>
								<td class="d-none d-sm-table-cell"><?php echo $speciesArray[3]?></td>
								<td><?=$species->count?></td>
								<td><a href="/site/<?php echo $title ?>/species/<?=$speciesArray[5]?>">see records</a></td>
							<?php endif?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>
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
		color: "#0996DB",
		weight: 5,
		opacity: 0.33
	});

	// Create a Layer Group and add to map
	const layers = L.layerGroup([minimal, boundary]);

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

<?= $this->include('pagination') ?>

<?php if (isset($download_link)) : ?>
	<p><a href="<?= $download_link ?>">Download this data</a></p>
<?php endif ?>

<?= $this->endSection() ?>
