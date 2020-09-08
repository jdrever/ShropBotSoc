<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<h2>Find Sites</h2>
<form action="<?=base_url('/sites/index/'); ?>" id="sites-select-form" name="sites-select-form" method="post" accept-charset="utf-8">
    <div class="form-group">
        <label for="sites-name">Enter all or part of a site name</label>
        <input type="text" class="form-control" name="site-name" id="site-name" 
            aria-describedby="site-name-help" placeholder="Enter a sites" value="<?=(isset($siteName)) ? $siteName : '' ?>">
        <small id="sites-name-help" class="form-text text-muted">Try something like "Aston".</small>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">List Sites</button>
    </div>
</form>

<?= $this->endSection() ?>