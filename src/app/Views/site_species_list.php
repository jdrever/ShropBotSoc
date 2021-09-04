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

<p>MAP OF SITE LOCATION</p>

<!-- Display search results -->
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

	<?= $this->include('pagination') ?>

	<?php if (isset($download_link)) : ?>
		<p><a href="<?= $download_link ?>">Download this data</a></p>
	<?php endif ?>

<?php endif ?>

<?= $this->endSection() ?>
