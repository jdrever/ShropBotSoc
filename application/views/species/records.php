<h2><?=urldecode($speciesName)?></h2>

<?php if (isset($records)):?>
    <table class="table">
        <thead><tr><th>Site</th><th>Location</th><th>Precision</th><th>Year</th></tr></thead>
        <tbody>
        <?php foreach ($records->occurrences as $record):?>
        <tr>
            <td>?</td><td><?=$record->decimalLatitude?>, <?=$record->decimalLongitude?></td>
            <td><?=$record->coordinateUncertaintyInMeters?></td>
            <td><?=$record->year?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif ?>