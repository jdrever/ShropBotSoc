<?php 
//we've sent a single row via row_array $details
echo '<div id = "details_head" align = "center">';
echo '<h3>Record Details</h3>';
echo '</div>';
display_array($details);

function display_array($this_array)
//Display as a two-column HTML table
{
    echo '<table align="center" width = "400px">';
    foreach ($this_array as $key => $value)
    {
        if (is_array($value))
        {
            display_array($value);
        }
        else
        {
             echo '<tr><td bgcolor="#F0F8FF">'.$key.'</td><td bgcolor="#F5F5DC">'.$value.'</td></tr>';
        }
    }
    echo '</table>';
}
      



?>
