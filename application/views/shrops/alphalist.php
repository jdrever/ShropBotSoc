<?php
//Construct a single row table with each letter of the alphabet a link to 
//a function in the controller

echo '<table width = "70%" align="center"><tr>';

$alphabet = range('A', 'Z'); 
$alphaarray = array();
foreach ($alphabet as $letter) { 
    $alphaarray[$letter] = anchor('shrops/getalpha/letter/'.$letter,$letter,'title="Species names alphabetically"');
 
echo '<td>'.$alphaarray[$letter].'</td>';
} 
echo '</tr></table>';

     
        ?>