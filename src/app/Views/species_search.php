<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<h2>Shropshire</h2>

<?= form_open('species') ?>
<div class="form-group row">
	<label for="search" class="col-sm-2 col-form-label d-none d-md-inline">Enter part of a species name</label>
	<div class="col-sm-6">
		<input type="text" class="form-control" name="search" id="search" aria-describedby="search-help" placeholder="Enter a species" value="<?= set_value('search'); ?>" />
		<small id="search-help" class="form-text text-muted d-none d-md-inline">Try something like "Hedera".</small>
	</div>
	<div class="col-sm-4">
		<button type="submit" class="btn btn-primary">List Species</button>
	</div>
	</label>
</div>
<div class="form-group row">
	<label for="in" class="col-md-2 col-form-label d-none d-md-inline">Search for</label>
	<div class="col-md-10">
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="name-type" id="scientific-name" value="scientific" <?= set_radio('name-type', 'scientific', true); ?> />
			<label class="form-check-label" for="scientific-name">
				scientific<span class="d-none d-md-inline"> name only</span>
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="name-type" id="axiophyte-name" value="axiophyte" <?= set_radio('name-type', 'axiophyte'); ?> />
			<label class="form-check-label" for="axiophyte-name">
				axiophyte<span class="d-none d-md-inline"> scientific name only</span>
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="name-type" id="common-name" value="common" <?= set_radio('name-type', 'common'); ?> />
			<label class="form-check-label" for="common-name">
				common<span class="d-none d-md-inline"> name only</span>
			</label>
		</div>
	</div>
</div>
<div class="form-group row">
	<label for="in" class="col-md-2 col-form-label d-none d-md-inline">Groups</label>
	<div class="col-md-10">
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="plants" value="plants" <?= set_radio('groups', 'plants', true); ?> />
			<label class="form-check-label" for="scientific-name">
				only plants
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="bryophytes" value="bryophytes" <?= set_radio('groups', 'bryophytes'); ?> />
			<label class="form-check-label" for="axiophyte-name">
				only bryophytes
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="both" value="both" <?= set_radio('groups', 'both'); ?> />
			<label class="form-check-label" for="common-name">
				both plants and bryophytes
			</label>
		</div>
	</div>
</div>
<?= form_close() ?>
<!-- Show the search results if there are any -->
<?php if (isset($speciesList)) : ?>
	<table class="table">
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
				<tr>
					<td class="d-none d-md-table-cell"><?= $species->family ?></td>
					<td><a href="<?= base_url('/species/' . $species->name) ?>"><?= $species->name ?></a></td>
					<td class="d-none d-sm-table-cell">
						<a href="<?= base_url('/species/' . $species->name) ?>"><?= $species->commonName ?></a>
					</td>
					<td><?= $species->count ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<nav>
		<ul class="pagination justify-content-center">
			<li class="page-item"><a class="page-link" href="#">Previous</a></li>
			<li class="page-item"><a class="page-link" href="#">1</a></li>
			<li class="page-item"><a class="page-link" href="#">2</a></li>
			<li class="page-item"><a class="page-link" href="#">3</a></li>
			<li class="page-item"><a class="page-link" href="#">Next</a></li>
		</ul>
	</nav>
<?php endif ?>
<?= $this->endSection() ?>
