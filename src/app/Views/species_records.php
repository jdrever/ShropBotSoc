// TODO: #38 change $snake_case to $camelCase variables
<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center">
	<a href="#" class="header-backArrow">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
		</svg>
	</a>
	<h2>
		<?= urldecode($species_name) ?> records in
		<?= urldecode($site_name) ?>
	</h2>
</div>
<?php if (isset($download_link)) : ?>
	<p><a href="<?= $download_link ?>">Download this data</a></p>
<?php endif ?>

<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#map-container" type="button" role="tab" aria-controls="map" aria-selected="true">Map</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="false">Data</button>
	</li>
</ul>

<div class="row tab-content">

	<div id="map-container" class="tab-pane fade show active col-lg">
		<div id="map" class=""></div>
	</div>

	<div id="data" class="tab-pane fade col-lg">
		<?php if (isset($records_list)) : ?>
			<table class="table">
				<thead>
					<tr>
						<th>Site</th>
						<th>Square</th>
						<th>Collector</th>
						<th>Year</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($records_list as $record) : ?>
						<tr>
							<td>
								<a href="/site/<?= $record->locationId ?>/group/plants/type/scientific">
									<?= $record->locationId ?>
								</a>
							</td>
							<td>
								<a href="/square/<?= $record->gridReference ?>/group/plants/type/scientific">
									<?= $record->gridReference ?>
								</a>
							</td>
							<td>
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
</div>
<script>
	//make a minimal base layer
	const minimal = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		maxZoom: 18,
		id: "mapbox/streets-v11",
		tileSize: 512,
		zoomOffset: -1,
		accessToken: "pk.eyJ1Ijoiam9lamNvbGxpbnMiLCJhIjoiY2tnbnpjZmtpMGM2MTJ4czFqdHEzdmNhbSJ9.Fin7MSPizbCcQi6hSzVigw"
	});

	//County boundary
	const url = "/data/shropshire.json";
	const boundary = L.geoJSON();
	fetch(url)
		.then((response) => response.json())
		.then((data) => boundary.addData(data));

	//OS Grid graticule
	const options = {};
	const graticule = L.osGraticule(options);

	//make a dot map layer
	const wmsUrl = "https://records-ws.nbnatlas.org/mapping/wms/reflect?"
		+ "Q=lsid:<?= $records_list[0]->speciesGuid ?>"
		+ "&ENV=colourmode:osgrid;color:ffff00;name:circle;size:4;opacity:0.5;"
		+ "gridlabels:true;gridres:singlegrid"
		+ "&fq=data_resource_uid:dr782";

	const species = L.tileLayer.wms(wmsUrl, {
		"layers": "ALA:occurrences",
		"uppercase": true,
		"format": "image/png",
		"transparent": true
	});
	//make a map and add the layers
	const map = L.map("map", {
		center: [52.6, -3.0],
		zoom: 9,
		layers: [minimal, graticule, boundary, species]
	});
</script>
<nav>
	<ul class="pagination justify-content-center">
		<li class="page-item"><a class="page-link" href="#">Previous</a></li>
		<li class="page-item"><a class="page-link" href="#">1</a></li>
		<li class="page-item"><a class="page-link" href="#">2</a></li>
		<li class="page-item"><a class="page-link" href="#">Next</a></li>
	</ul>
</nav>
<?= $this->endSection() ?>
