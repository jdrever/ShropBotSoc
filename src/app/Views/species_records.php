<?php echo $this->extend('default') ?>
<?php echo $this->section('content') ?>
<h2><?php echo urldecode($speciesName)?></h2>
<p><a href="https://records-ws.nbnatlas.org/occurrences/index/download?q=data_resource_uid:dr782&fq=taxon_name:<?php echo $speciesName?>&reasonTypeId=11&fileType=csv">Download this data</a></p>
<?php if (isset($records)):?>
    <table class="table">
        <thead><tr><th>Site</th><th>Square</th><th>Collector</th><th>Year</th><th>Details</th></tr></thead>
        <tbody>
        <?php foreach ($records as $record):?>
        <tr>
            <td>
              <a href="/species/site/<?php echo $record->locationId?>"><?php echo $record->locationId?></a>
            </td>
            <td><a href="/square/<?php echo $record->gridReference?>"><?php echo $record->gridReference?></a></td>
            <td><?php echo $record->collector?></td>
            <td><?php echo $record->year?></td>
            <td><a href="<?php echo base_url("/records/{$record->uuid}");?>">details...</td>
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
<?php echo $this->endSection() ?>
