<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta content="Promoting the enjoyment, understanding and conservation of the flora of Shropshire" name="description" />
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<!-- Custom styles for this template -->
	<link href="/css/from-blogger.css" rel="stylesheet">
	<link href="/css/enhancements.css" rel="stylesheet">
	<!-- Mapping -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" integrity="sha256-BPfK9M5v34c2XP6p0cxVz1mUQLst0gTLk0mlc7kuodA=" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet-src.js" integrity="sha256-dG7rUAec0UN0e3nlMEuYJzsbK0rpoz4hBbNp0ir9iF8=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="/js/L.OSGraticule.js"></script>
	<script type="text/javascript" src="/js/leaflet.wms.js"></script>
	<title>
		<?= $title ?>
	</title>
</head>

<body>
	<nav class="navbar navbar-expand-sm navbar-light">
		<a class="navbar-brand" href="/">Botanical Records</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="navbar-collapse collapse" id="navbar-main">
			<ul class="nav navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="/species">Species</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/sites">Sites</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/squares">Squares</a>
				</li>
			</ul>
			<ul class="nav navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="/about">About</a>
				</li>
				</li>
			</ul>
		</div>
	</nav>
	<?php if (isset($error)) : ?>
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<?= $error ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif ?>
	<div class="container-fluid content-inner">
		<?= $this->renderSection('content') ?>
	</div>
	<footer class="footer-fluid">
		<div class="container">
			<span class="text-muted">Shropshire Botanical Society Data supported by
				<a href="https://registry.nbnatlas.org/public/show/dp120" target="_blank">National Biodiversity Network</a>
			</span>
		</div>
	</footer>
	<!-- Bootstrap 5-beta2 bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
</body>

</html>
