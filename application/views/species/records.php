<h2><?=urldecode($speciesName)?></h2>

<?= $records ?>

<?php if (isset($records)):?>
    <table class="table">
        <thead><tr><th>Site</th><th>Location</th><th>Collector</th><th>Year</th></tr></thead>
        <tbody>
        <?php foreach ($records as $record):?>
        <tr>
            <td>?</td>
            <td><a href="http://www.google.com/maps/place/<?=$record->decimalLatitude?>,<?=$record->decimalLongitude?>">Google Map</a></td>
            <td><?=$record->collector?></td>
            <td><?=$record->year?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif ?>


