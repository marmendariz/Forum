<?
    include_once 'lib.php';
    set_path();
    force_ssl();
    session_start();
    auto_login();
?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <link rel='icon' type='image/x-icon' href='img/Q.png'>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Navigation </title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <link rel="stylesheet" type="text/css" href="slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css" />
    <script src="js/vendor/modernizr.js"></script>
  </head>
  <body>

<?php

    include_once 'header.php';
   
/********** Boolean Flags *********/
$cat_search_executed = false;
 
/******** If the form has been submitted *******/

    if (isset($_POST['submit2'])) {   

        $searchstat = true;

       if(!isset($_POST['search_field']) || empty($_POST['search_field'])) {
           $searchstat = false;
           echo "enter something!";
        } else {
            $cat_input_search = input_clean($_POST['search_field']);
             if(!preg_match('/^[a-zA-Z-]+$/',$cat_input_search))
                $searchstat = false;
        }
     
        if ($searchstat) {
          
            if(!($db = db_connect())){
                echo 'Database error<br>';
                exit;
            }   

/*********** What type of Search *************/

    $search_type = $_POST['search_type'];
                
/*************** Category ******************/

    if ($search_type == "CategoriesOption" || $search_type == "AllOption") {

    $query = "SELECT * FROM category WHERE cat_name LIKE '%" . $cat_input_search . "%' OR cat_text LIKE '%" . $cat_input_search . "%'";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $cat_input_search);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows();
    $stmt->bind_result($cat_id_verified, $cat_name_verified, $cat_level_verified, $cat_text_verified, $parent_cat_id_verified);
    if ($rows) {
        $cat_search_executed = true;
        echo "<h3>The Following Categories Were Found:<br><br>";
        while ($stmt->fetch()) {
            echo "<a href='show_child_cat.php?cat_id=$cat_id_verified'><h3 style='color:#008cbb;'>$cat_name_verified</h3>";
        echo "<p>&nbsp &nbsp &nbsp &nbsp$cat_text_verified</p>";
        echo "<hr>";
        }
    } else {
        echo "<br><br>Sorry, no results were found.";
        exit;
    } 

    }


/*************** Discussion ******************/

    if ($search_type == "DiscussionsOption" || $search_type == "AllOption") {

    $query = "SELECT * FROM discussion WHERE dis_name LIKE '%" . $cat_input_search . "%' OR dis_text LIKE '%" . $cat_input_search . "%'";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $cat_input_search);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows();
    $stmt->bind_result($dis_id_verified, $dis_name_verified, $dis_text_verified, $dis_flag_verified, $dis_upvote_count, $dis_downvote_count);
    if ($rows) {
        echo "<h3>The Following Discussions Were Found:<br><br>";
        while ($stmt->fetch()) {
            echo "<a href='discussion.php?dis_id=$dis_id_verified'><h3 style='color:#008cbb;'>$dis_name_verified</h3>";
        echo "<p>&nbsp &nbsp &nbsp &nbsp$dis_text_verified</p>";
        echo "<hr>";
        }
    } else {
        echo "<br><br>Sorry, no results were found.";
        exit;
    } 

    }






    }

}
?>


<div class="row">
        <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>
            <div class='slick_class'>
                <center><a href='show_parent_cat.php'><h1><img src="img/Logo.png"></h1></a></center>
                <center><a href='show_child_cat.php?cat_id=2'><h1><img src="img/Math.png"></h1></a></center>
                <center><a href='show_child_cat.php?cat_id=6'><h1><img src="img/Computer Science.png"></h1></a></center>
            </div>
 
<h3> Search Details </h3>
<p> Search Category Titles and Text</p>
<form method = 'post' action = 'search_submit.php' id = searchform>
<input type = 'text' name ='search_field'>
<input type = 'submit' name = 'submit2' class='button' value = 'Search'>
</form>


       
        </div>
</div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="slick/slick.min.js"></script>
    <script type="text/javascript">
        $(document).foundation();
        
        var addAncHide = true;
        $(document).ready(function(){

            $('#addAncSection').hide();

            $('#addAnc').on('click', function(){
                var button = $('#addAnc');
                if(addAncHide){
                    $('#addAncSection').show();
                    addAncHide = false;
                    button.val('Collapse');
                }
                else{
                    $('#addAncSection').hide();
                    addAncHide = true;
                    button.val('Expand');
                }
            });


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
