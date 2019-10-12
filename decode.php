<?php

$records_url = "https://records-ws.nbnatlas.org/occurrences/search?q=data_resource_uid:dr782&fq=taxon_name:Abies%20alba&sort=taxon_name&fsort=index&pageSize=12";
$records_json = file_get_contents($records_url);
$get_records = json_decode($records_json);

echo json_encode($get_records, JSON_PRETTY_PRINT);



?>