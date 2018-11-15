


<?php
$page_title="Species Distribution Map";



?>

<table align = 'center'>
<tr><td></td></tr>

<tr><td align = 'center'>
<b><font size='5'><?php echo 'Tetrad distribution of <i>' . $speciesname['sppName']; ?></i> <?php echo  ' (' . $speciesname['sppCommon'] . ') '; ?> in Shropshire</font> </b>
</td></tr>
<tr><td></td></tr>
<tr><td align = 'center'><font color='darkblue'>Click on a dot to view records for the tetrad</font></td></tr>
<tr>
<td align = 'center'>
<form method = 'post' action='<?php echo base_url();?>shrops/records_for_map_tetrad/species/<?php echo "$speciesname[sppid]"; ?> '>

<input type='image' src= '<?php echo base_url();?>shrops/makemap/sppid/<?php echo "$speciesname[sppid]";?>' alt='Shropshire' 
     width = '521' height = '605' border = '0' name = 'map' onMouseMove='DisplayCoords(this)'>

</form>
</td>

<tr><td align='center'>
<img src='<?php echo base_url();?>assets/images/bluedot.gif'>&nbsp;pre-1980&nbsp;&nbsp;&nbsp;<img src='<?php echo base_url();?>assets/images/greendot.gif'>&nbsp;1980 onward
</td></tr>


<tr><td></td></tr>

</table>






