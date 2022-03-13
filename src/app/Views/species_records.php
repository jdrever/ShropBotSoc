<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>

<?php if ($status ==='OK') : ?>

<div class="d-flex align-items-center">
	<a href="/species/<?=$speciesNameSearch ?>/group/<?=$speciesGroup ?>/type/<?=$speciesNameType ?>/axiophyte/<?=$axiophyteFilter?>" class="header-backArrow">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
		</svg>
	</a>
	<h2>
		<?= urldecode($displayName) ?> records in
		Shropshire
	</h2>
</div>

<?php if (isset($download_link)) : ?>
	<p><a href='<?= $download_link ?>''>Download this data</a></p>
<?php endif ?>

<ul id="tabs" class="nav nav-tabs d-lg-none" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active fw-bold" id="profile-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="true">Data</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link fw-bold" id="home-tab" data-bs-toggle="tab" data-bs-target="#map-container" type="button" role="tab" aria-controls="map" aria-selected="false">Map</button>
	</li>
</ul>
<p><?= $totalRecords ?> records</p>
<div id="tab-content" class="row">
	<div id="data" class="tab-pane fade show active col-lg">
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
							<?php if (isset($record->gridReference)) : ?>
								<a href="/square/<?= $record->gridReference ?>/species/<?=$speciesName ?>">
									<?= $record->gridReference ?>
								</a>
							<?php endif ?>
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
	<div id="map-container" class="tab-pane fade show col-lg">
		<div id="map" class=""></div>
	</div>
</div>
<script>
	// Initialise the map
	const map = initialiseBasicMap();

	// Make a dot map layer
	const wmsUrl = "https://records-ws.nbnatlas.org/mapping/wms/reflect?" +
		"Q=lsid:<?= $speciesGuid ?>" +
		"&ENV=colourmode:osgrid;color:ffff00;name:circle;size:4;opacity:0.5;" +
		"gridlabels:true;gridres:singlegrid" +
		"&fq=data_resource_uid:dr782";

	const species = L.tileLayer.wms(wmsUrl, {
		"layers": "ALA:occurrences",
		"uppercase": true,
		"format": "image/png",
		"transparent": true
	});

	species.addTo(map);

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
</script>

<?= $this->include('pagination') ?>

<?php if (isset($download_link)) : ?>
	<p><a href='<?= $download_link ?>''>Download this data</a></p>
<?php endif ?>

<?php endif ?>

<?= $this->endSection() ?>
