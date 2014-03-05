<?php

echo '<h2>' . $tetrad_header . '</h2>';
if (isset($links)) {echo "<div class='center' id ='pagination'>" . $links . "</div>";}
$tmpl = array('table_open'=>'<table id = "site_names_table" border = "0" width="70" cellspacing="2" cellpadding="5" align="center">',
'heading_row_start' => '<tr bgcolor = "#ccccff">',
'row_start' => '<tr bgcolor = "#CCCCCC">',
'row_alt_start' => '<tr bgcolor = "#99CCCC">'
 );//Move this to a templates file and require it?


$this->table->set_template($tmpl);
$this->table->set_heading('Tetrads');


echo $this->table->generate($tetrads);

//$tetrad_list is sent from the Controller, after calling the Model
?>
