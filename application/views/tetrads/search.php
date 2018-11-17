<h1>Find Tetrads</h1>

<form action="http://localhost/botanical_records/shrops/tetrads/" class="select_form" id="tetrad_select_form" name="tetradselectform"
    method="post" accept-charset="utf-8">
    <div id=findtetrads>Find Records for Tetrads</div>
    <table id="tetrads_form_table" border="0" cellpadding="4">
        <tr>
            <td><label for="tetradname" id="tetrad_label">Enter all or part of a tetrad identifier e.g SJ40C</label></td>
            <td><input type="text" name="tetradname" value="" id="tetradname" maxlength="5" />
            </td>
        </tr>
        <tr>
            <td>OR - <a href="http://localhost/botanical_records/shrops/findtetradsfrommap">Get tetrad from a map</a></td>
            <td></td>
        </tr>
        <tr>
            <td><input type="submit" name="submit_tetrads" value="List Tetrads" />
                <input type="reset" name="reset_tetrads" value="Reset" enable_tetrad_name() />
            </td>
            <td></td>
        </tr>
    </table>
</form>