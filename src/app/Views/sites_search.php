<?php echo $this->extend('default') ?>
<?php echo $this->section('content') ?>
<h2>Find Sites</h2>

<?php echo form_open('sites') ?>
    <div class="form-group row">
        <label for="search" class="col-sm-2 col-form-label d-none d-md-inline">Enter all or part of a site name</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="search" id="search" 
                aria-describedby="search-help" placeholder="Enter a site"
                value="<?php echo set_value('search'); ?>" />
            <small id="search-help" class="form-text text-muted d-none d-md-inline">Try something like "Aston".</small>
        </div>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-primary">List Sites</button>
        </div>
        </label>
    </div>
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
</form>

<?php if (isset($sites)):?>
    <table class="table">
        <thead><tr>
            <th>Site</th>
            <th>Record Count</th>
        </tr></thead>
        <tbody>
        <?php foreach ($sites as $site):?>
        <tr>
            <td><a href="<?php echo base_url("/species/site/{$site->label}");?>"><?php echo$site->label?></a></td>
            <td><?php echo$site->count?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </nav>
<?php endif ?>
<?php echo $this->endSection() ?>