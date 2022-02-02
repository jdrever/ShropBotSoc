<?= $this->extend('default') ?>
<?= $this->section('content') ?>

<style>
    #map {
      max-width: 960px;
      height: 500px;
    }
    svg {
      position: relative;
    }
    .square {
      fill: #000;
      fill-opacity: .2;
      stroke-width: 1px;
    }
    .c0 {
      stroke: #fff;
    }
    .c1 {
      stroke: red;
    }
    .c2 {
      stroke: blue;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.3/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.3/styles/a11y-light.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.3/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

<script src="https://unpkg.com/brc-atlas-bigr/dist/bigr.min.umd.js"></script>
<script src="https://d3js.org/d3.v5.min.js"></script>

<h2>Find A Square</h2>
<div class="alert alert-info" role="alert">
	PLEASE NOTE: this page is currently still under development and may not return accurate information.
</div>

<div class="form-group row">
	<label for="in" class="col-md-2 col-form-label d-none d-md-inline">Groups</label>
	<div class="col-md-10">
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="plants" value="scientific" <?= set_radio('groups', 'plants', true); ?> />
			<label class="form-check-label" for="scientific-name">
				only plants
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="bryophytes" value="axiophyte" <?= set_radio('groups', 'bryophytes'); ?> />
			<label class="form-check-label" for="axiophyte-name">
				only bryophytes
			</label>
		</div>
		<div class="form-check form-check-inline">
			<input class="form-check-input" type="radio" name="species-group" id="both" value="common" <?= set_radio('groups', 'both'); ?> />
			<label class="form-check-label" for="common-name">
				both plants and bryophytes
			</label>
		</div>
	</div>
</div>

<p id="selection"> Select a square </p>

<?php if (isset($message)) : ?>
	<div class="alert alert-danger" role="alert">
		I am very sorry, but an error has occured.</b>:  <?= $message ?>">
	</div>
<?php endif ?>

<div id="map"></div>
<script>
	// Initialise the map
	const map = initialiseBasicMap(true, false)

	L.control.locate().addTo(map)

	var svg = d3.select(map.getPanes().overlayPane).append("svg")
    var g = svg.append("g").attr("class", "leaflet-zoom-hide")
    var transform = d3.geoTransform({point: projectPoint})
    var path = d3.geoPath().projection(transform)
    var ftrSquares, squares

	function onMapClick(e) {
		var grs = bigr.getGrFromCoords(e.latlng.lng, e.latlng.lat, 'wg', '', [100000, 10000, 5000,1000])
		// TODO - go to species list for square page
    	alert(grs.p1000)
	}

	map.on('click', onMapClick)
    map.on("zoomend", reset)
    map.on("mousemove", function(e) {
      	var grs = bigr.getGrFromCoords(e.latlng.lng, e.latlng.lat, 'wg', '', [100000, 10000, 5000,1000])

      	if (!grs.p100000) return

      	var ftr1 = {
        	type: 'Feature',
        	geometry: bigr.getGjson(grs.p100000, 'wg', 'square')
      	}
      	var ftr2 = {
        	type: 'Feature',
        	geometry: bigr.getGjson(grs.p10000, 'wg', 'square')
      	}
      	var ftr3 = {
        	type: 'Feature',
        	geometry: bigr.getGjson(grs.p5000, 'wg', 'square')
      	}
      	var ftr4 = {
        	type: 'Feature',
        	geometry: bigr.getGjson(grs.p1000, 'wg', 'square')
      	}

      	ftrSquares = [ftr1, ftr2, ftr3, ftr4] //,
      	squares = g.selectAll("path")
		  	.data(ftrSquares)

      	squares.enter()
        	.append("path")
        	.attr("d", path)
        	.attr("class", function(d, i) {
				// Add ci and square classes to style the path of the highlighted square
          		return 'c' + i + ' square'
        	})

		// Update currently selected square
		document.getElementById("selection").innerHTML = "Currently selecting: <b>" + grs.p1000 + "</b>";

      	reset()
    });

	function reset() {
		var bounds = path.bounds({
			type: "FeatureCollection",
			features: ftrSquares
		})

		var topLeft = bounds[0]
		var bottomRight = bounds[1]

		svg.attr("width", bottomRight[0] - topLeft[0])
			.attr("height", bottomRight[1] - topLeft[1])
			.style("left", topLeft[0] + "px")
			.style("top", topLeft[1] + "px")

		g.attr("transform", "translate(" + -topLeft[0] + "," + -topLeft[1] + ")")
		squares.attr("d", path)
	}

    function projectPoint(x, y) {
		var point = map.latLngToLayerPoint(new L.LatLng(y, x))
    	this.stream.point(point.x, point.y)
    }
</script>

<?= $this->endSection() ?>
