<?php

$tmpl = array('table_open'=>'<table border = "0" width="800" cellspacing="2" cellpadding="5" align="center">',
'heading_row_start' => '<tr bgcolor = "#ccccff">',
'row_start' => '<tr bgcolor = "#CCCCCC">',
'row_alt_start' => '<tr bgcolor = "#99CCCC">'
 );

echo '<h2>Displaying records for Tetrad '. $tetrads. ' - '.$total_rows. ' total</h2>';
echo "<div class='center' id ='pagination'>" . $links . "</div>";
 

$this->table->set_template($tmpl);
$this->table->set_heading('','Latin Name','Common Name','Group','Grid Ref','Year');
echo $this->table->generate($the_records);

//$the_records is sent from the Controller, after calling the Model

//The model has produced a csv version of the query results which are also passed
//to the Controller, which in turns passes them here as csvdata

//Now set up a form which will call a function in the controller
//that forces download of the csvdata as a file
echo "<div class='center'  id='csvdiv'>";
$hidden = array('csv_qry_str' => $csv_qry_str);

$formatts = array('id' => 'getcsvform');
echo form_open('shrops/get_csv', $formatts, $hidden);


$btnatts = array(
    'name' => 'submit',
    'id' => 'csvbutton',
    'value' => 'true',
    'type' => 'submit',
    'content' => 'Download as CSV'
);

echo form_button($btnatts);

echo "</div>";
?>
