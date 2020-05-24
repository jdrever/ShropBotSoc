<h1>Find Sites</h1>

<form action="<?php echo base_url('/sites/search/'); ?>" id="sites-select-form" name="sites-select-form" method="post"
    accept-charset="utf-8">

    <div class="form-group">
        <label for="site-name">Enter all or part of a site name</label>
        <input type="text" class="form-control" id="site-name" aria-describedby="site-name-help" placeholder="Enter a site name">
        <small id="site-name-help" class="form-text text-muted">Try something like "Wood".</small>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="all-sites-check">
        <label class="form-check-label" for="all-sites-check">Search on common names</label>
    </div>

    <button type="submit" class="btn btn-primary">List Sites</button>
</form>