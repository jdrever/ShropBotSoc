<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta content='Promoting the enjoyment, understanding and conservation of the flora of Shropshire' name='description'/>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <!-- Custom styles for this template -->
  <link href="/css/from-blogger.css" rel="stylesheet">
  <title>
    <?=$title?>
  </title>
</head>

<body>
  <nav class="navbar navbar-expand-sm navbar-light">
    <a class="navbar-brand" href="/">Botanical Records</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-main" aria-controls="navbarSupportedContent"
      aria-expanded="false" aria-label="Toggle navigation">
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
          <a class="nav-link" href="/contact">Contact</a></li>
        </li>
      </ul>
    </div>
  </nav>
  <?php if (isset ($error)):?>
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <?=$error?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <?php endif;?>
  <div class="container-fluid content-inner">
    <?= $this->renderSection('content') ?>
  </div>
  <footer class="footer-fluid">
    <div class="container">
      <span class="text-muted">Shropshire Botanical Society Data from</span>
    </div>
  </footer>
<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>