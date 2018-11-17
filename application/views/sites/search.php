<h1>Find Sites</h1>

<form action="<?php echo base_url('/sites/search/'); ?>" id="sites-select-form" name="sites-select-form"
    method="post" accept-charset="utf-8">

    <div id=findsites>Find Records for Sites</div>
    <table id="sites_form_table" border="0" cellpadding="4">
        <tr>
            <td><label for="sitename" id="site_name_label">Enter all or part of a site name</label></td>
            <td><input type="text" name="sitename" value="" id="sitename" maxlength="50" />
            </td>
        </tr>
        <tr>
            <td><label for="all_sites_label" id="all_sites_check_label">OR - check the box to browse all sites</label></td>
            <td><input type="checkbox" name="all-sites" value="ischecked" id="allsites" class="regular-checkbox"
                    onClick="disable_site_name()" />
            </td>
        </tr>
        <tr>
            <td><input type="submit" name="submit_sites" value="List Sites" />
                <input type="reset" name="reset_sites" value="Reset" enable_site_name() />
            </td>
            <td></td>
        </tr>
    </table>
</form>