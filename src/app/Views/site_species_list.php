<?= $this->extend('default') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center">
	<a href="/sites/<?=$siteNameSearch ?>" class="header-backArrow">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
			<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
		</svg>
	</a>
	<h2>Records in <?= $title ?></h2>
</div>

<div class="alert alert-info" role="alert">
	PLEASE NOTE: this page is currently still under development and may not return accurate information.
</div>

<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>

<p>MAP OF SITE LOCATION</p>

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
		<tr>
			<td class="d-none d-md-table-cell"><?php echo $species->family?></td>
			<td><?=$species->name?></td>
			<td class="d-none d-sm-table-cell"><?php echo $species->commonName?></td>
			<td><?=$species->count?></td>
			<td><a href="/site/<?php echo $title ?>/species/<?=$species->name?>">see records</a></td>
		</tr>
		<?php endforeach ?>
		</tbody>
	</table>
	<nav>
		<ul class="pagination justify-content-center">
			<li class="page-item"><a class="page-link" href="#">Previous</a><li>
			<li class="page-item"><a class="page-link" href="#">1</a></li>
			<li class="page-item"><a class="page-link" href="#">2</a></li>
			<li class="page-item"><a class="page-link" href="#">3</a></li>
			<li class="page-item"><a class="page-link" href="#">Next</a></li>
		</ul>
	</nav>
<?php endif ?>

<?= $this->endSection() ?>
