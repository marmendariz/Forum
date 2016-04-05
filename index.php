<?
    include_once 'lib.php';
    set_path();
    force_ssl();
    session_start();
?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <link rel='icon' type='image/x-icon' href='img/Q.png'>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Home </title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <link rel="stylesheet" type="text/css" href="slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>

<?php
    include_once 'header.php';
    $login_failed = false;

/*
    $query = 'select user_id, ann_id, ann_date from admin_create_ann';
    $stmt = $db->prepare($query);
    $stmt -> execute();
    $stmt -> store_result();
    $stmt -> bind_result($user_id, $ann_id, $ann_date);
    $stmt -> fetch();
 */

?>

<div class="row">
        <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>
            <div class='slick_class'>
                <center><a href='show_parent_cat.php'><h1><img src="img/Logo.png"></h1></a></center>
                <center><a href='show_child_cat.php?cat_id=2'><h1><img src="img/Math.png"></h1></a></center>
                <center><a href='show_child_cat.php?cat_id=6'><h1><img src="img/Computer Science.png"></h1></a></center>
            </div>
        
        </div>
</div>

<div class="row">
        <div class="large-11 large-centered columns panel text-center medium-11 medium-centered small-11 small-centered">
                <h3 class='text-center'>Welcome to Quadcore Forum! </h3>
                <h4 class='text-center'>Some Interesting Information for all to see! </h4>
        </div>
</div>
<div class = "row">
     <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>

<?php 
    
    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }

    $username = input_clean($_SESSION['valid_user']);
    $query = 'select ann_id, ann_name, ann_text from announcement ORDER BY ann_id desc';
    $stmt = $db->prepare($query);
    //$stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($ann_id, $ann_name, $ann_text);
    
    echo "<h3 style='color: #008cbb' >Announcements: </h3>";
        
    for ($i = 0; $i < 5; $i++) {
        if($stmt->fetch()) { 
        echo " <div class='text-left'> ";
        echo "<hr>";
        echo " <h4> $ann_name </h4>";
        echo " <h5> &nbsp &nbsp &nbsp &nbsp $ann_text </h5> ";
        echo "</div> ";
        }
    }

?>
    </div>
</div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <!---<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>-->
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="slick/slick.min.js"></script>
    <script type="text/javascript">
        $(document).foundation();
        $(document).ready(function(){

            var width = $(window).width();
            var height = $(window).height();
            
            if(width <= 1023 && height <= 768){
                $('.slick_class').slick({
                    dots: false,
                    swipeToSlide: true,
                    arrows:false,
                    autoplay:true,
                    autoplaySpeed: 3000,
                    infinite: true,
                    speed: 800,
                    slidesToShow: 1,
                    adaptiveHeight: false
                });
            }
            else{
                $('.slick_class').slick({
                    dots: true,
                    swipeToSlide: false,
                    arrows:false,
                    autoplay:true,
                    autoplaySpeed: 3000,
                    infinite: true,
                    speed: 800,
                    slidesToShow: 1,
                    adaptiveHeight: false
                });

            }
        });
    </script>
  </body>
</html>
