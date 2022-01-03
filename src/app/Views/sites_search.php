<?= $this->extend('default') ?>
<?= $this->section('content') ?>

<div class="alert alert-info" role="alert">
	PLEASE NOTE: this page is currently still under development and may not return accurate information.
</div>

<?= form_open('sites') ?>
<div class="row mb-2">
	<div class="col-lg-8 mx-auto">
		<label for="search" class="form-label visually-hidden">Site name</label>
		<div class="input-group">
			<input type="text" class="form-control" name="search" id="search" aria-describedby="search-help" placeholder="Enter a site" value="<?= set_value('search', $siteSearchString); ?>" />
			<button type="submit" class="btn btn-primary">List Sites</button>
		</div>
		<small id="search-help" class="form-text text-start text-md-center d-block">Enter all or part of a site name. Try something like "Aston".</small>
	</div>
</div>
<?= form_close() ?>

<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>

<?php if (isset($sites) && count($sites) > 0) : ?>
	<table class="table mt-3">
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
						<a href="<?= base_url('/site/' . $site->label . '/group/both/type/scientific'); ?>">
							<?= $site->label ?>
						</a>
					</td>
					<td><?= $site->count ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<?= $this->include('pagination') ?>

<?php elseif (!empty($siteSearchString)) : ?>
	<div class="alert alert-warning" role="alert">
		No records could be found matching those criteria.
	</div>
<?php endif ?>

<?= $this->endSection() ?>
