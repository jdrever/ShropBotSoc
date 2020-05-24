<h2><?=urldecode($speciesName)?></h2>
<p><a href="https://records-ws.nbnatlas.org/occurrences/index/download?q=data_resource_uid:dr782&fq=taxon_name:<?=$speciesName?>&reasonTypeId=11&fileType=csv">Download this data</a></p>
<?php if (isset($records)):?>
    <table class="table">
        <thead><tr><th>Site</th><th>Location</th><th>Collector</th><th>Year</th></tr></thead>
        <tbody>
        <?php foreach ($records as $record):?>
        <tr>
            <td>?</td>
            <td><a target="_blank" href="http://www.google.com/maps/place/<?=$record->decimalLatitude?>,<?=$record->decimalLongitude?>">Google Map</a></td>
            <td><?=$record->collector?></td>
            <td><?=$record->year?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif ?>


