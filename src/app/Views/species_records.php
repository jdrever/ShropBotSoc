<?php echo $this->extend('default') ?>
<?php echo $this->section('content') ?>
<h2>Records - <?php echo urldecode($site_name)?> - <?php echo urldecode($species_name)?></h2>

<?php if (isset($download_link)):?>
<p><a href="<?php echo $download_link?>">Download this data</a></p>
<?php endif ?>

<div id="map" style="height:400px;width:500px"></div>
<script>
//make a minimal base layer 
var minimal = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1Ijoiam9lamNvbGxpbnMiLCJhIjoiY2tnbnpjZmtpMGM2MTJ4czFqdHEzdmNhbSJ9.Fin7MSPizbCcQi6hSzVigw'
});

//County boundary
const url = '/data/shropshire.json';
var boundary = L.geoJSON()
fetch(url).then(function(response) {
        return response.json();
    }).then(function(data) {
        boundary.addData(data);
    });

//OS Grid graticule
var options = {};
var graticule = L.osGraticule(options);

//make a dot map layer
var wmsUrl = "https://records-ws.nbnatlas.org/mapping/wms/reflect?";
  wmsUrl += "Q=lsid:<?php echo $records_list[0]->speciesGuid?>";
  wmsUrl += "&ENV=colourmode:osgrid;color:ffff00;name:circle;size:4;opacity:0.5;gridlabels:true;gridres:singlegrid";
  wmsUrl += "&fq=data_resource_uid:dr782";

var species = L.tileLayer.wms(
    wmsUrl, {
    "layers": "ALA:occurrences",
    "uppercase": true,
    "format": "image/png",
    "transparent": true
});
//make a map and add the layers
   var map = L.map('map', {
      center: [52.6, -3.0],
      zoom: 9,
      layers: [minimal, graticule, boundary, species]
   });

</script>

<?php if (isset($records_list)):?>
    <table class="table">
        <thead><tr><th>Site</th><th>Square</th><th>Collector</th><th>Year</th><th>Details</th></tr></thead>
        <tbody>
        <?php foreach ($records_list as $record):?>
        <tr>
            <td>
              <a href="/species/site/<?php echo $record->locationId?>"><?php echo $record->locationId?></a>
            </td>
            <td><a href="/square/<?php echo $record->gridReference?>"><?php echo $record->gridReference?></a></td>
            <td><?php echo $record->collector?></td>
            <td><?php echo $record->year?></td>
            <td><a href="<?php echo base_url("/records/{$record->uuid}");?>">more</td>
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
