<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<h2><?=urldecode($speciesName)?></h2>
<p><a href="https://records-ws.nbnatlas.org/occurrences/index/download?q=data_resource_uid:dr782&fq=taxon_name:<?=$speciesName?>&reasonTypeId=11&fileType=csv">Download this data</a></p>
<?php if (isset($records)):?>
    <table class="table">
        <thead><tr><th>Site</th><th>Location</th><th>Collector</th><th>Year</th></tr></thead>
        <tbody>
        <?php foreach ($records as $record):?>
        <tr>
            <td><?=$record->locationId?></td>
            <td><a target="_blank" href="http://www.google.com/maps/place/<?=$record->decimalLatitude?>,<?=$record->decimalLongitude?>">Google Map</a></td>
            <td><?=$record->collector?></td>
            <td><?=$record->year?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif ?>

<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">Next</a></li>
    </ul>
</nav>
<?= $this->endSection() ?>
