<!DOCTYPE html>
<html>
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-136975293-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-136975293-1');
        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" type="text/css" media="screen" charset="utf-8">
        <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
        <script src="<?php echo base_url('assets/js/shrops.js'); ?>" type="javascipt"></script>
    </head>
    <body>
        <div id ="header-img-container">
            <img  id ="header-img"src ="<?php echo base_url('assets/images/shropshire-botanical-society.png'); ?>"  >
        </div>
        <div class="menulinks"><a href="<?php echo base_url('/'); ?>" title="Shropshire Database Home">Start Page</a></div>
        <?php
            $this->load->view($main_content);
        ?>
        <div id="footer">
            <p>Page rendered in <strong>{elapsed_time}</strong> seconds</p>
        </div>
    </body>
</html>
