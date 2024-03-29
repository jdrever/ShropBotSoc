<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>

<div class="d-flex align-items-center">
	<a href="javascript:history.back()" class="header-backArrow">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
		</svg>
	</a>
	<h2>
		Species in <?= $siteName ?>
	</h2>
</div>

<?= form_open('sites', '', array('site-name'=>$siteName)) ?>
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
			<input class="form-check-input" type="checkbox" name="axiophyte-filter" id="axiophyte-filter" value="true"  onchange="this.form.submit();" <?= set_radio('axiophyte-filter', 'true', ($axiophyteFilter === 'true')); ?> />
			<label class="form-check-label" for="axiophyte-name">
				<span class="d-lg-none">axiophytes</span>
				<span class="d-none d-lg-inline">axiophytes only</span>
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
<?php if (isset($speciesList) && count($speciesList) > 0) : ?>

	<?php if (isset($downloadLink)) : ?>
	<p><a href="<?= $downloadLink ?>">Download this data</a></p>
	<?php endif ?>
	<div id="tab-content" class="row">
		<div id="data" class="tab-pane fade show active col-lg">
			<table class="table">
				<thead><tr>
					<th class="d-none d-md-table-cell">Family</th>
					<?php if ($nameType === 'scientific') : ?>
					<th>Scientific Name</th>
					<th class="d-none d-sm-table-cell">Common Name</th>
					<?php endif?>
					<?php if ($nameType === 'common') : ?>
					<th class="d-none d-sm-table-cell">Scientific Name</th>
					<th>Common Name</th>
					<?php endif?>
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
								<td><a href="/site/<?php echo $siteName ?>/species/<?=$speciesArray[0]?>">see records</a></td>
							<?php endif?>
							<?php if ($nameType === 'common') : ?>
								<td class="d-none d-md-table-cell"><?php echo $speciesArray[5]?></td>
								<td class="d-none d-sm-table-cell"><?=$speciesArray[1]?></td>
								<td><?php echo $speciesArray[3]?></td>
								<td><?=$species->count?></td>
								<td><a href="/site/<?php echo $siteName ?>/species/<?=$speciesArray[1]?>">see records</a></td>
							<?php endif?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<div id="map-container" class="tab-pane fade show col-lg">
			<div id="map" class=""></div>
		</div>
	</div>
	<script>
		// Initialise the map
		const map = initialiseBasicMap();

		// Unless the first occurrence didn't contain a site location, create a
		// marker for the site's location
		<?php if (!empty($siteLocation)) : ?>
		const siteMarker = L.marker(<?= json_encode($siteLocation) ?>, {
			opacity: 0.75
		});
		siteMarker.addTo(map);
		<?php endif ?>
	</script>

	<?= $this->include('pagination') ?>

	<?php if (isset($downloadLink)) : ?>
		<p><a href="<?= $downloadLink ?>">Download this data</a></p>
	<?php endif ?>

<?php else: ?>
	<div class="alert alert-warning" role="alert">
		No records could be found matching those criteria.
	</div>
<?php endif ?>

<?= $this->endSection() ?>
