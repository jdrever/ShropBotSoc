function disable_species_name() {
    if (document.getElementById("allspecies").checked) {
        document.getElementById("speciesname").disabled = true;

        document.getElementById("speciesname").value = 'Browse All Species';
    }
    else {
        document.getElementById("speciesname").disabled = false;

        if (document.getElementById("speciesname").value == 'Browse All Species') document.getElementById("speciesname").value = '';
    }
}

function enable_species_name() {
    document.getElementById("speciesname").disabled = false;
}

function disable_site_name() {
    if (document.getElementById("allsites").checked) {
        document.getElementById("sitename").disabled = true;

        document.getElementById("sitename").value = 'Browse All Sites';
    }
    else {
        document.getElementById("sitename").disabled = false;

        if (document.getElementById("sitename").value == 'Browse All Sites') document.getElementById("sitename").value = '';
    }
}

function enable_site_name() {
    document.getElementById("sitename").disabled = false;
}

function disable_tetrad_name() {
    if (document.getElementById("alltetrads").checked) {
        document.getElementById("tetradname").disabled = true;

        document.getElementById("tetradname").value = 'Browse All Tetrads';
    }
    else {
        document.getElementById("tetradname").disabled = false;

        if (document.getElementById("tetradname").value == 'Browse All Tetrads') document.getElementById("tetradname").value = '';
    }
}

function enable_site_name() {
    document.getElementById("tetradname").disabled = false;
}
