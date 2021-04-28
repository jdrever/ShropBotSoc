<?= $this->extend('default') ?>
<?= $this->section('content') ?>

<h2>Species in a Square</h2>

<div class="alert alert-info" role="alert">
	PLEASE NOTE: this page is currently still under development and may not return accurate information.
</div>

<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>
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
			<td><?=$species->scientificName?></td>
			<td class="d-none d-sm-table-cell"><?php echo $species->vernacularName?></td>
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
