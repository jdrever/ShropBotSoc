
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" href="/assets/css/style.css" type="text/css" media="screen" charset="utf-8">
        <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
        <script src="/assets/js/shrops.js"></script>
    </head>
    <body >
        <div id ="header-img-container">
            <img  id ="header-img"src ="/assets/images/stiperstones.png"  >
        </div>
        <?php
        $start_page = '/index.php/shrops';
        $other_start_page = '/index.php';
        $current_url = $_SERVER['REQUEST_URI'];
        if ($current_url != $start_page and $current_url != $other_start_page )
        echo '<div class="menulinks">' . anchor ('shrops','Start page','Shropshire Database Home') . '</div>';
        ?>
     
        

