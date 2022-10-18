<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center">
	<?php if ($status === 'OK') : ?>
		<a href="javascript:history.back()" class="header-backArrow">
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
			<button class="nav-link active fw-bold" id="map-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="true">Data</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link fw-bold" id="data-tab" data-bs-toggle="tab" data-bs-target="#map-container" type="button" role="tab" aria-controls="map" aria-selected="false">Map</button>
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
						<td scope="row"><a href="/square/<?= $gridReference ?>/species/<?= $classification->scientificName ?>"><?= $gridReference ?></a></td>
					</tr>
					<tr>
						<td scope="row">Recorders</td>
						<td scope="row"><?= $occurrence->recordedBy ?></td>
					</tr>
					<tr>
						<td scope="row">Full Date</td>
						<td scope="row"><?= $fullDate ?></td>
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
						<td scope="row">
						<?php if (isset($classification->vernacularName)) : ?>
							<?= $classification->vernacularName ?>
						<?php endif ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="map-container" class="tab-pane fade show col-lg">
			<div id="map" class=""></div>
		</div>
	</div>
	<?php if (isset($location->gridReferenceWKT)) : ?>
		<script>
			// Basic map code (in BasicMap.js) - not fitting to Shropshire
			const map = initialiseBasicMap(fitToShropshire = false);

			// Initialise geoJson boundary layer for wkt polygon
			const wktBoundary = L.geoJSON(null, {
				"color": "#0996DB",
				"weight": 5,
				"opacity": 0.33
			});

			// Create outline of record location and zoom to fit to it
			const wkt = new Wkt.Wkt();
			wkt.read("<?= $location->gridReferenceWKT ?>");
			const wktRecord = wkt.toJson();
			wktBoundary.addData(wktRecord);
			wktBoundary.addTo(map)
			map.fitBounds(wktBoundary.getBounds(wktRecord).pad(0.5));
		</script>
	<?php endif ?>

	<?php if (isset($download_link)) : ?>
		<p><a href="<?= $download_link ?>">Download this data</a></p>
	<?php endif ?>

<?php endif ?>
<?= $this->endSection() ?>
