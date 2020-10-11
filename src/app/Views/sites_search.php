<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<h2>Find Sites</h2>

<form action="<?=base_url('/sites/index/'); ?>" method="get" accept-charset="utf-8">
    <div class="form-group row">
        <label for="search" class="col-sm-2 col-form-label d-none d-md-inline">Enter all or part of a site name</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="search" id="search" 
                aria-describedby="search-help" placeholder="Enter a site"
                value="<?php echo isset($search) ? $search : '' ?>">
            <small id="search-help" class="form-text text-muted d-none d-md-inline">Try something like "Aston".</small>
        </div>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-primary">List Sites</button>
        </div>
        </label>
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
            <td><a href="#"><?=$site->label?></a></td>
            <td><?=$site->count?></td>
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
<?= $this->endSection() ?>