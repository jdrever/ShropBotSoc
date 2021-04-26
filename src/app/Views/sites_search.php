<?= $this->extend('default') ?>
<?= $this->section('content') ?>

<h2>Find Sites</h2>

<div class="alert alert-info" role="alert">
	PLEASE NOTE: this page is currently still under development and may not return accurate information.
</div>

<?= form_open('sites') ?>
<div class="form-group row">
	<label for="search" class="col-sm-2 col-form-label d-none d-md-inline">Enter all or part of a site name</label>
	<div class="col-sm-6">
		<input type="text" class="form-control" name="search" id="search" aria-describedby="search-help" placeholder="Enter a site" value="<?= set_value('search'); ?>" />
		<small id="search-help" class="form-text text-muted d-none d-md-inline">Try something like "Aston".</small>
	</div>
	<div class="col-sm-4">
		<button type="submit" class="btn btn-primary">List Sites</button>
	</div>
	</label>
</div>
<div class="form-group row">
	<label for="in" class="col-md-2 col-form-label d-none d-md-inline">Groups</label>
	<div class="col-md-10">
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="plants" value="scientific" <?= set_radio('groups', 'plants', true); ?> />
			<label class="form-check-label" for="scientific-name">
				only plants
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="bryophytes" value="axiophyte" <?= set_radio('groups', 'bryophytes'); ?> />
			<label class="form-check-label" for="axiophyte-name">
				only bryophytes
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="both" value="common" <?= set_radio('groups', 'both'); ?> />
			<label class="form-check-label" for="common-name">
				both plants and bryophytes
			</label>
		</div>
	</div>
</div>
</form>

<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>

<?php if (isset($sites)) : ?>
	<table class="table">
		<thead>
			<tr>
				<th>Site</th>
				<th>Record Count</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($sites as $site) : ?>
				<tr>
					<td>
						<a href="<?= base_url('/site/' . $site->label . '/group/plants/type/scientific'); ?>">
							<?= $site->label ?>
						</a>
					</td>
					<td><?= $site->count ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<nav>
		<ul class="pagination justify-content-center">
			<li class="page-item"><a class="page-link" href="#">Previous</a></li>
			<li class="page-item"><a class="page-link" href="#">1</a></li>
			<li class="page-item"><a class="page-link" href="#">Next</a></li>
		</ul>
	</nav>
<?php endif ?>
<?= $this->endSection() ?>
