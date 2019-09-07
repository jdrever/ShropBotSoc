
<?php
//Construct a single row table with each letter of the alphabet a link to 
//a function in the controller
if ($alpha_links == TRUE)
{

echo '<h2>Browse Taxa Alphabetically</h2>';
echo '<table id = "alpha-menu" width = "70%" align="center"><tr>';
//echo "Name Type is :" . $name_type;
$alphabet = range('A', 'Z'); 
$alphaarray = array();
foreach ($alphabet as $letter) { 
    $alphaarray[$letter] = anchor('shrops/species/letter/'.$letter.'/group/'.$taxon_group.'/name_type/'.$name_type.'/axio/'.$axio,$letter,'title="Species names alphabetically"');
 
echo '<td>'.$alphaarray[$letter].'</td>';
} 
echo '</tr></table>';



}
$group_name=$groups[$taxon_group];
if ($name_type=='sppCommon') $name_type_display = 'Common'; else $name_type_display = 'Latin';
if ($taxon_group==0) $group_message = "Showing taxa in All Groups  (by " . $name_type_display . " name) matching your search term";
else {
    $group_message = "Showing taxa in ".$group_name." only (by " . $name_type_display . " name) matching your search term";
}

?>
<div class="center"> <?php echo $group_message ?> </div>
<?php

$tmpl = array('table_open'=>'<table id = "species_names_table" border = "0" width="700" cellspacing="2" cellpadding="5" align="center">',
'heading_row_start' => '<tr bgcolor = "#ccccff">',
'row_start' => '<tr bgcolor = "#CCCCCC">',
'row_alt_start' => '<tr bgcolor = "#99CCCC">'
 );//Move this to a templates file and require it?


$this->table->set_template($tmpl);
$this->table->set_heading('Latin Name','Common Name','Axiophyte','Group');


echo $this->table->generate($species_list);

//$species_list is sent from the Controller, after calling the Model

?>
