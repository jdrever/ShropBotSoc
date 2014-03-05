
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css" 
              type="text/css" media="screen"  charset=utf-8">
        
        <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
        
        <SCRIPT LANGUAGE="JavaScript"><!--
function disable_species_name() {

if(document.getElementById("allspecies").checked)
{
document.getElementById("speciesname").disabled=true;

document.getElementById("speciesname").value='Browse All Species';
}

else
{
document.getElementById("speciesname").disabled=false;

if (document.getElementById("speciesname").value=='Browse All Species') document.getElementById("speciesname").value='';
}
}
function enable_species_name() {
    document.getElementById("speciesname").disabled=false;
}


function disable_site_name() {

if(document.getElementById("allsites").checked)
{
document.getElementById("sitename").disabled=true;

document.getElementById("sitename").value='Browse All Sites';
}

else
{
document.getElementById("sitename").disabled=false;

if (document.getElementById("sitename").value=='Browse All Sites') document.getElementById("sitename").value='';
}
}
function enable_site_name() {
    document.getElementById("sitename").disabled=false;
}



function disable_tetrad_name() {

if(document.getElementById("alltetrads").checked)
{
document.getElementById("tetradname").disabled=true;

document.getElementById("tetradname").value='Browse All Tetrads';
}

else
{
document.getElementById("tetradname").disabled=false;

if (document.getElementById("tetradname").value=='Browse All Tetrads') document.getElementById("tetradname").value='';
}
}
function enable_site_name() {
    document.getElementById("tetradname").disabled=false;
}
//-->
</SCRIPT>
    </head>
    <body >
        <div id ="header-img-container">
            
        <img  id ="header-img"src ="<?php echo base_url(); ?>assets/images/stiperstones.png"  >
      
        </div>
        <?php
        $start_page = '/sbtest/index.php/shrops';
        $other_start_page = '/sbtest/index.php';
        $current_url = $_SERVER['REQUEST_URI'];
        if ($current_url != $start_page and $current_url != $other_start_page )
        echo '<div class="menulinks">' . anchor ('shrops','Start page','Shropshire Database Home') . '</div>';
        ?>
     
        

