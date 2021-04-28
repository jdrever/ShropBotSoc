<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<div class="d-flex align-items-center">
	<a href="#" class="header-backArrow">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
		</svg>
	</a>
	<h2>
		<?= urldecode($speciesName) ?> records in
		<?= urldecode($siteName) ?>
	</h2>
</div>

<div class="alert alert-info" role="alert">
	PLEASE NOTE: this page is currently still under development and may not return accurate information.
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
<div id="tab-content" class="row">

	<div id="map-container" class="tab-pane fade show active col-lg">
		<div id="map" class="">Map not yet developed</div>
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
