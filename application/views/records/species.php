

<h1>Find Records for Species</h1>
<div id="start_page_error_species">
<?php if (isset ($error) && $type == 'species') echo $error;?>
</div>
<form action="<?php echo base_url('/records/species/'); ?>" class="select_form" id="species_select_form" name="speciesselectform"
    method="post" accept-charset="utf-8">

    <table id="species_form_table">
        <tr>
            <td><label for="speciesname" id="species_name_label">Enter all or part of a species name</label></td>
            <td><input type="text" name="speciesname" value="" id="speciesname" maxlength="50" />
            </td>
        </tr>
        <tr>
            <td><label for="all_species_label" id="all_species_check_label">OR - check the box to browse all species</label></td>
            <td><input type="checkbox" name="all-species" value="ischecked" id="allspecies" class="regular-checkbox"
                    onClick="disable_species_name()" />
            </td>
        </tr>
        <tr>
            <td><label for="taxon-group" id="taxon_group_label">Choose a species group</label></td>
            <td>
                <select name="taxon-group" id="taxongroup">
                    <?php foreach ($groups as $key => $group):?>
                    <option value="<?=$key?>"><?=$group?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="axiosonly" id="axioslabel">Show axiophytes only</label></td>
            <td><input type="checkbox" name="axiosonly" value="ischecked" id="axiosonly" class="regular-checkbox" />
            </td>
        </tr>
        <tr>
            <td><label for="common_names" id="common_names_label">Search on common names</label></td>
            <td><input type="checkbox" name="search_common" value="ischecked" id="common" class="regular-checkbox" />
            </td>
        </tr>
        <tr>
            <td><input type="submit" name="submit_species" value="List Species" />
                <input type="reset" name="reset_species" value="Reset" enable_species_name() />
            </td>
            <td></td>
        </tr>
    </table>
</form>