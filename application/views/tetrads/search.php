<h1>Find Tetrads</h1>

<form action="<?php echo base_url('/tetrads/search/'); ?>" id="tetrads-select-form" name="tetrads-select-form" method="post"
    accept-charset="utf-8">
    <div class="form-group">
        <label for="tetrad-name">Enter all or part of a tetrad identifier </label>
        <input type="text" class="form-control" id="tetrad-name"  maxlength="5" aria-describedby="tetrad-name-help" placeholder="Enter a tetrad identifier">
        <small id="species-name-help" class="form-text text-muted">Try something like "SJ40C".</small>
    </div>
    <div class="form-group">
        <p>OR - <a href="<?php echo base_url('/tetrads/map/'); ?>">Get tetrad from a map</a></p>
    </div>
    <button type="submit" class="btn btn-primary">List Tetrads</button>
</form>
