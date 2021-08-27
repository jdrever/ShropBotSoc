<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<h2 class="text-start text-md-center">Search for a Species in Shropshire</h2>

<?= form_open('species') ?>

<div class="row mb-2">
	<div class="col-lg-8 mx-auto">
		<label for="search" class="form-label visually-hidden">Species name</label>
		<div class="input-group">
			<input type="text" id="search" class="form-control" name="search" aria-describedby="search-help" placeholder="Species name" value="<?= set_value('search', $nameSearchString); ?>" />
			<button type="submit" class="btn btn-primary">List Species</button>
		</div>
		<small id="search-help" class="form-text text-start text-md-center d-block">Enter all or part of a species name. Try something like "Hedera".</small>
	</div>
</div>
<div class="row justify-content-center gy-3">
	<div class="form-group col-sm-4 col-lg-3">
		<div class="form-check">
			<input class="form-check-input" type="radio" name="name-type" id="scientific-name" value="scientific" onchange="if (this.form.search.value!='') { this.form.submit(); }" <?= set_radio('name-type', 'scientific', ($nameType === 'scientific')); ?> />
			<label class="form-check-label" for="scientific-name">
				scientific<span class="d-none d-lg-inline"> name only</span>
			</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="name-type" id="common-name" value="common"  onchange="if (this.form.search.value!='') { this.form.submit(); }" <?= set_radio('name-type', 'common', ($nameType === 'common')); ?> />
			<label class="form-check-label" for="common-name">
				common<span class="d-none d-lg-inline"> name only</span>
			</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="name-type" id="axiophyte-name" value="axiophyte"  onchange="if (this.form.search.value!='') { this.form.submit(); }" <?= set_radio('name-type', 'axiophyte'); ?> disabled />
			<label class="form-check-label" for="axiophyte-name">
				<span class="d-lg-none">axiophyte names</span>
				<span class="d-none d-lg-inline">axiophyte scientific name only</span>
			</label>
		</div>
	</div>
	<div class="form-group col-sm-4 col-lg-3">
		<div class="form-check">
			<input class="form-check-input" type="radio" name="species-group" id="plants" value="plants"  onchange="if (this.form.search.value!='') { this.form.submit(); }" <?= set_radio('groups', 'plants', $speciesGroup === 'plants'); ?> />
			<label class="form-check-label" for="plants">
				only plants
			</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="species-group" id="bryophytes" value="bryophytes"  onchange="if (this.form.search.value!='') { this.form.submit(); }" <?= set_radio('groups', 'bryophytes', $speciesGroup === 'bryophytes'); ?> />
			<label class="form-check-label" for="bryophytes">
				only bryophytes
			</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="species-group" id="both" value="both"  onchange="if (this.form.search.value!='') { this.form.submit(); }" <?= set_radio('groups', 'both', $speciesGroup === 'both'); ?> />
			<label class="form-check-label" for="both">
				both <span class="d-none d-xl-inline">plants and bryophytes</span>
			</label>
		</div>

	</div>
</div>
<?= form_close() ?>


<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>
	</div>
<?php endif ?>

<!-- Show the search results if there are any -->
<?php if (isset($speciesList)&&count($speciesList)>0) : ?>
	<?php if (isset($downloadLink)) : ?>
	<p><a href="<?= $downloadLink ?>">Download this data</a></p>
	<?php endif ?>
	<table class="table mt-3">
		<thead>
			<tr>
				<th class="d-none d-md-table-cell">Family</th>
				<th>Scientific Name</th>
				<th class="d-none d-sm-table-cell">Common Name</th>
				<th>Records</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($speciesList as $species) : ?>
				<?php $speciesArray = explode('|', (string)$species->label); ?>
				<tr>
					<td class="d-none d-md-table-cell"><?= $speciesArray[5] ?></td>
					<td><a href="<?= base_url('/species/' . $speciesArray[1] . '?nameSearchString=' . $nameSearchString) ?>"><?= $speciesArray[1] ?></a></td>
					<td class="d-none d-sm-table-cell">
						<a href="<?= base_url('/species/' . $speciesArray[1] . '?displayName=' . $speciesArray[0] . '&nameSearchString=' . $nameSearchString) ?>"><?= $speciesArray[0] ?></a>
					</td>
					<td><?= $species->count ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<nav>
		<ul class="pagination justify-content-center">
	<?php
	$range = 3;
	if ($page + $range >7) : ?>
			<li class="page-item"><a class="page-link" href="<?= current_url() . '?page=1' ?>">First</a></li>
	<?php endif ?>
	<?php if ($page > 1) : ?>
			<li class="page-item"><a class="page-link" href="<?= current_url() . '?page=' . ($page - 1) ?>">Previous</a></li>
	<?php endif ?>
	<?php
		for ($x = ($page - $range); $x < (($page + $range) + 1); $x++)
		{
			if (($x > 0) && ($x <= $totalPages))
			{
				if ($x === $page)
				{
					?>

			<li class="page-item"><span class="page-link" style="font-color:#000000;"><?= $x?></span></li>
		<?php
				}
				else
				{
					?>
 			<li class="page-item"><a class="page-link" href="<?= current_url() . '?page=' . $x?> "><?= $x?></a></li>
		<?php
			}
		}
	} ?>
	<?php
	if ($page<$totalPages) : ?>
			<li class="page-item"><a class="page-link" href="<?= current_url() . '?page=' . ($page+1) ?>">Next</a></li>
	<?php endif ?>
	<?php
	if (($page + $range)<$totalPages) : ?>
			<li class="page-item"><a class="page-link" href="<?= current_url() . '?page=' .$totalPages?>">Last</a></li>
	<?php endif ?>
	</ul>
	<p class="text-center" style="font-size:small;"><?= $totalRecords ?> records in <?= $totalPages ?> pages </p>
</nav>


	<?php if (isset($downloadLink)) : ?>
		<p><a href="<?= $downloadLink ?>">Download this data</a></p>
	<?php endif ?>
<?php else: ?>
	<?php if (! empty($nameSearchString)) : ?>
	<div class="alert alert-warning" role="alert">
		No records could be found matching those criteria.
	</div>
	<?php endif ?>
<?php endif ?>

<?= $this->endSection() ?>
