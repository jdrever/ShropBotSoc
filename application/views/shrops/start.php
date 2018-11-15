
<?php

$spp_form_atts = array(
            'class' => 'select_form', 
            'id' => 'species_select_form',
            'name' => 'speciesselectform')
          ;

echo form_open('shrops/species/',$spp_form_atts);

echo '<div id="findspecies">Find Records for Species</div>'; 

echo '<table id = "species_form_table" border="0" cellpadding="4">';
$spp_name_label_atts = array(
    'id' => 'species_name_label',
   
);
echo '<tr><td>'.form_label('Enter all or part of a species name', 'speciesname', $spp_name_label_atts).'</td>';

$atts = array(
              'name'        => 'speciesname',
              'id'          => 'speciesname',
              'maxlength'   => '50',
              'type'        => 'text'
              );
echo '<td>'.form_input($atts). '</td></tr>';

$showallspeciesatts = array(
    'name'        => 'all-species',
    'id'          => 'allspecies',
    'class'       => 'regular-checkbox',
    'checked'     => FALSE,
    'value'       => 'ischecked',
    'onClick'     => 'disable_species_name()'
    );
//$js_all_species = 'onClick="disable_species_name()"';

$all_species_check_label_atts = array(
    'id' => 'all_species_check_label',
);
echo '<tr><td>'.form_label('OR - check the box to browse all species', 'all_species_label',$all_species_check_label_atts ).'</td>';
echo '<td>'.form_checkbox($showallspeciesatts). '</td></tr>';

//echo '<td>'.form_checkbox('all-species','ischecked',FALSE,$js_all_species). '</td></tr>';


$taxon_group_atts = array(
    'id' => 'taxon_group_label'  
);

echo '<tr><td>'.form_label('Choose a species group', 'taxon-group',$taxon_group_atts).'</td>';

$groupatts = 'id= "taxongroup"';
//print_r($groups) ;   
echo '<td>'.form_dropdown('taxon-group', $groups,'0',$groupatts). '</td></tr>';
//Parameters are: name,options list,selected index,other atts

$axios_label_atts = array(
    'id' => 'axioslabel',
);

echo  '<tr><td>'.form_label('Show axiophytes only', 'axiosonly',$axios_label_atts).'</td>';
$axioatts = array(
    'name'        => 'axiosonly',
    'id'          => 'axiosonly',
    'class'       => 'regular-checkbox',
    'value'       => 'ischecked',
    'checked'     => FALSE
    );

echo '<td>'.form_checkbox($axioatts). '</td></tr>';

$common_label_atts = array(
    'id' => 'common_names_label',
   
);
echo  '<tr><td>'.form_label('Search on common names', 'common_names',$common_label_atts).'</td>';
$common_name_atts = array(
    'name'        => 'search_common',
    'id'          => 'common',
    'class'       => 'regular-checkbox',
    'value'       => 'ischecked',
    'checked'     => FALSE
    );

echo '<td>'.form_checkbox($common_name_atts). '</td></tr>';


echo '<tr><td>'.form_submit('submit_species', 'List Species');
echo form_reset('reset_species', 'Reset','enable_species_name()').'</td>';
echo '<td></td></tr></table>';
echo  form_close();


echo '<div id="start_page_error_species">';
if (isset ($error) && $type == 'species') echo $error;
echo '</div>';
//
// End of species section **********************************************************
//


//Start of Sites section
$site_form_atts = array(
            'class' => 'select_form', 
            'id' => 'site_select_form',
            'name' => 'siteselectform')
          ;

echo form_open('shrops/sites/',$site_form_atts);

echo '<div id=findsites>Find Records for Sites</div>';

echo '<table id = "sites_form_table" border="0" cellpadding="4">';
$site_name_label_atts = array(
    'id' => 'site_name_label',
   
);
echo '<tr><td>'.form_label('Enter all or part of a site name', 'sitename',$site_name_label_atts).'</td>';

$site_name_atts = array(
              'name'        => 'sitename',
              'id'          => 'sitename',
              'maxlength'   => '50',
              'type'        => 'text'
              );
echo '<td>'.form_input($site_name_atts). '</td></tr>';

$showallsitesatts = array(
    'name'        => 'all-sites',
    'id'          => 'allsites',
    'class'       => 'regular-checkbox',
    'checked'     => FALSE,
    'value'       => 'ischecked',
    'onClick'     => 'disable_site_name()'
    );
//$js_all_species = 'onClick="disable_species_name()"';

$all_sites_check_label_atts = array(
    'id' => 'all_sites_check_label',
);
echo '<tr><td>'.form_label('OR - check the box to browse all sites', 'all_sites_label',$all_sites_check_label_atts ).'</td>';
echo '<td>'.form_checkbox($showallsitesatts). '</td></tr>';


echo '<tr><td>'.form_submit('submit_sites', 'List Sites');
echo form_reset('reset_sites', 'Reset','enable_site_name()').'</td>';
echo '<td></td></tr></table>';
echo  form_close();

echo '<div id="start_page_error_sites">';
if (isset ($error) && $type == 'sites') echo $error;
echo '</div>';
//End of Sites section


// Start of Tetrads section

$tetrad_form_atts = array(
            'class' => 'select_form', 
            'id' => 'tetrad_select_form',
            'name' => 'tetradselectform')
          ;

echo form_open('shrops/tetrads/',$tetrad_form_atts);

echo '<div id=findtetrads>Find Records for Tetrads</div>';

echo '<table id = "tetrads_form_table" border="0" cellpadding="4">';
$tetrad_label_atts = array(
    'id' => 'tetrad_label',
   
);
echo '<tr><td>'.form_label('Enter all or part of a tetrad identifier e.g SJ40C', 'tetradname',$tetrad_label_atts).'</td>';

$tetrad_atts = array(
              'name'        => 'tetradname',
              'id'          => 'tetradname',
              'maxlength'   => '5',
              'type'        => 'text'
              );
echo '<td>'.form_input($tetrad_atts). '</td></tr>';

$showalltetrads_atts = array(
    'name'        => 'all-tetrads',
    'id'          => 'alltetrads',
    'class'       => 'regular-checkbox',
    'checked'     => FALSE,
    'value'       => 'ischecked',
    'onClick'     => 'disable_tetrad_name()'
    );
//$js_all_species = 'onClick="disable_species_name()"';

$all_tetrads_check_label_atts = array(
    'id' => 'all_tetrads_check_label',
);
//echo '<tr><td>'.form_label('OR - check the box to browse all tetrads', 'all_tetrads_label',$all_tetrads_check_label_atts ).'</td>';
//echo '<td>'.form_checkbox($showalltetrads_atts). '</td></tr>';

echo '<tr><td>OR - ' . anchor('shrops/findtetradsfrommap','Get tetrad from a map') . '</td><td></td></tr>';
echo '<tr><td>'.form_submit('submit_tetrads', 'List Tetrads');
echo form_reset('reset_tetrads', 'Reset','enable_tetrad_name()').'</td>';
echo '<td></td></tr></table>';
echo  form_close();

echo '<div id="start_page_error_tetrads">';
if (isset ($error) && $type == 'tetrads') echo $error;
echo '</div>';
// End of Tetrads section

?>



