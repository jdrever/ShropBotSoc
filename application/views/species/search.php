<h1>Find Species</h1>

<form action="<?php echo base_url('/species/search/'); ?>" id="species-select-form" name="species-select-form" method="post"
    accept-charset="utf-8">
    <div class="form-group">
        <label for="species-name">Enter all or part of a species name</label>
        <input type="text" class="form-control" id="species-name" aria-describedby="species-name-help" placeholder="Enter a species">
        <small id="species-name-help" class="form-text text-muted">Try something like "Farus".</small>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="allSpeciesCheck">
        <label class="form-check-label" for="allSpeciesCheck">OR - check the box to browse all species</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="axiophytes-only-check">
        <label class="form-check-label" for="axiophytes-only-check">Show axiophytes only</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="common-names-check">
        <label class="form-check-label" for="common-names-check">Search on common names</label>
    </div>
    <div class="form-group">
        <label for="taxon-group">Choose a species group</label>
        <select name="taxon-group" id="taxon-group">
            <?php foreach ($groups as $group):?>
            <option value="<?=$group->grpId?>">
                <?=$group->grpName?>
            </option>
            <?php endforeach;?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">List Species</button>
</form>