<h1>Find Species</h1>

<form action="<?php echo base_url('/records/species/'); ?>" id="species_select_form" name="speciesselectform"
    method="post" accept-charset="utf-8">

    <div class="form-group">
        <label for="speciesName">Enter all or part of a species name</label>
        <input type="text" class="form-control" id="speciesName" aria-describedby="speciesNameHelp" placeholder="Enter a species">
        <small id="speciesNameHelp" class="form-text text-muted">Try something like "Farus".</small>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="allSpeciesCheck">
        <label class="form-check-label" for="allSpeciesCheck">OR - check the box to browse all species</label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="axiophytesOnlyCheck">
        <label class="form-check-label" for="axiophytesOnlyCheck">Show axiophytes only</label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="commonNamesCheck">
        <label class="form-check-label" for="commonNamesCheck">Search on common names</label>
    </div>

    <div class="form-group">
        <label for="taxonGroup">Choose a species group</label>
        <select name="taxon-group" id="taxonGroup">
            <?php foreach ($groups as $group):?>
            <option value="<?=$group->grpId?>">
                <?=$group->grpName?>
            </option>
            <?php endforeach;?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">List Species</button>
</form>