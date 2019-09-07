


<?php
$page_title="Map to return tetrads";



?>

<table align = 'center'>
<tr><td></td></tr>

<tr><td align = 'center'>
<h1>Click on the Map to return a tetrad</h1>
</td></tr>
<tr><td></td></tr>
<tr><td align = 'center'><font color='darkblue'Click on a dot to view records for the tetrad</font></td></tr>
<tr>
<td align = 'center'>
<form method = 'post' action='<?php echo base_url();?>index.php/shrops/all_records_for_map_tetrad/'>

<input type='image' src= '<?php echo base_url();?>index.php/shrops/make_base_map/' alt='Shropshire' 
     width = '521' height = '605' border = '0' name = 'map' >

</form>
</td>

<tr><td align='center'>
<img src='<?php echo base_url();?>assets/images/bluedot.gif'>&nbsp;pre-1980&nbsp;&nbsp;&nbsp;<img src='<?php echo base_url();?>assets/images/greendot.gif'>&nbsp;1980 onward
</td></tr>


<tr><td></td></tr>

</table>






