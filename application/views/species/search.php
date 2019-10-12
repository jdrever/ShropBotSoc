<h2>Find Species</h2>

<form action="<?=base_url('/species/index/'); ?>" id="species-select-form" name="species-select-form" method="post"
    accept-charset="utf-8">
    <div class="form-group">
        <label for="species-name">Enter all or part of a species name</label>
        <input type="text" class="form-control" name="species-name" id="species-name" 
            aria-describedby="species-name-help" placeholder="Enter a species" value="<?=(isset($speciesName)) ? $speciesName : '' ?>">
        <small id="species-name-help" class="form-text text-muted">Try something like "Farus".</small>
    </div>
    <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" name="axiophytes-only-check" id="axiophytes-only-check" <?=(isset($axiophytesOnlyCheck)) ? 'checked="checked"' : '' ?>">
        <label class="form-check-label" for="axiophytes-only-check">Show axiophytes only</label>
    </div>
    <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" name="common-names-check" id="common-names-check" <?=(isset($commonNamesCheck)) ? 'checked="checked"' : '' ?>">
        <label class="form-check-label" for="common-names-check">Search common names</label>
    </div>
    <div class="form-group">
        <label for="taxon-group">Group</label>
        <select name="taxon-group" id="taxon-group">
            <?php isset($groupSelected) ? : $groupSelected = $groups[0]->name ?>
            <?php foreach ($groups as $group):?>
                <?php if ($group->count > 0):?>
                <option <?=($groupSelected == $group->name ? 'selected' : '')?>>
                    <?=$group->name?> 
                </option>
                <?php endif ?>
            <?php endforeach;?>
        </select>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">List Species</button>
    </div>
</form>

<?php if (isset($taxa)):?>
    <table class="table">
        <thead><tr><th>Name</th><th>Common Name</th><th>Count</th></tr></thead>
        <tbody>
        <?php foreach ($taxa as $taxon):?>
        <tr>
            <td><a href="<?=base_url("/species/records/$taxon->name");?>"><?=$taxon->name?></a></td>
            <td><?=$taxon->commonName?></td>
            <td><?=$taxon->count?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
<?php endif ?>
