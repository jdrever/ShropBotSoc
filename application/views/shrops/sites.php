<?php

if ($alpha_links == TRUE)
{

echo '<h2>Browse Sites Alphabetically</h2>';
echo '<table id="alpha-menu-sites" width = "70%" align="center"><tr>';

$alphabet = range('A', 'Z'); 
$alphaarray = array();
foreach ($alphabet as $letter) { 
    $alphaarray[$letter] = anchor('shrops/sites/letter/'.$letter,$letter,'title="Site names alphabetically"');
 
echo '<td>'.$alphaarray[$letter].'</td>';
} 
echo '</tr></table>';



}
echo '<h2>Sites Matching Your Search Term</h2>';
$tmpl = array('table_open'=>'<table id = "site_names_table" border = "0" width="700" cellspacing="2" cellpadding="5" align="center">',
'heading_row_start' => '<tr bgcolor = "#ccccff">',
'row_start' => '<tr bgcolor = "#CCCCCC">',
'row_alt_start' => '<tr bgcolor = "#99CCCC">'
 );//Move this to a templates file and require it?


$this->table->set_template($tmpl);
$this->table->set_heading('ID','Site Name');


echo $this->table->generate($sites_list);
//$species_list is sent from the Controller, after calling the Model
?>
