<h1>Find Species</h1>

<form action="<?php echo base_url('/species/index/'); ?>" id="species-select-form" name="species-select-form" method="post"
    accept-charset="utf-8">
    <div class="form-group">
        <label for="species-name">Enter all or part of a species name</label>
        <input type="text" class="form-control" name="species-name" id="species-name" 
            aria-describedby="species-name-help" placeholder="Enter a species" value="<?=(isset($speciesName)) ? $speciesName : '' ?>">
        <small id="species-name-help" class="form-text text-muted">Try something like "Farus".</small>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="axiophytes-only-check" id="axiophytes-only-check" <?=(isset($axiophytesOnlyCheck)) ? 'checked="checked"' : '' ?>">
        <label class="form-check-label" for="axiophytes-only-check">Show axiophytes only</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="common-names-check" id="common-names-check" <?=(isset($commonNamesCheck)) ? 'checked="checked"' : '' ?>">
        <label class="form-check-label" for="common-names-check">Search common names</label>
    </div>
    <div class="form-group">
        <select name="taxon-group" id="taxon-group">
            <?php isset($groupSelected) ? : $groupSelected = 0 ?>
            <?php foreach ($groups as $group):?>
            <option value="<?=$group->grpId?>" <?=($groupSelected == $group->grpId ? 'selected' : '')?>>
                <?=$group->grpName?> 
            </option>
            <?php endforeach;?>
        </select>
        <label for="taxon-group">Species group</label>
    </div>
    <button type="submit" class="btn btn-primary">List Species</button>
</form>

