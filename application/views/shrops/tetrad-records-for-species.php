<?php
//Displays records of an individual species in a particular tetrad
$tmpl = array('table_open'=>'<table id = "records_by_species_by_tetrad_table" border = "0" width="700" cellspacing="2" cellpadding="5" align="center">',
'heading_row_start' => '<tr bgcolor = "#ccccff">',
'row_start' => '<tr bgcolor = "#CCCCCC">',
'row_alt_start' => '<tr bgcolor = "#99CCCC">'
 );//Move this to a templates file and require it?

echo '<h2>Displaying records for <em>'.$thespecies['sppName']. '</em> in Tetrad '. $thetetrad. '</h2>';
$this->table->set_template($tmpl);
$this->table->set_heading('Site Name','Grid Reference','Recorder','Year');


echo $this->table->generate($tetrecsforspp);


?>
