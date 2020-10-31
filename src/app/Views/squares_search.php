<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<h2>Find A Square</h2>
<div class="form-group row">
    <label for="in" class="col-md-2 col-form-label d-none d-md-inline">Groups</label>
    <div class="col-md-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="species-group"  id="plants"
                value="scientific" <?php echo set_radio('groups', 'plants', TRUE); ?> />
            <label class="form-check-label" for="scientific-name">
                only plants
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="species-group"  id="bryophytes"
                value="axiophyte" <?php echo set_radio('groups', 'bryophytes'); ?> />
            <label class="form-check-label" for="axiophyte-name">
                only bryophytes
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="species-group" id="both"
                value="common" <?php echo set_radio('groups', 'both'); ?> />
            <label class="form-check-label" for="common-name">
                both plants and bryophytes
            </label>
        </div>
    </div>
</div>
<?= $this->endSection() ?>