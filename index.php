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

?>

<div class="row">
        <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>
            <div class='slick_class'>
                <center><a href='show_parent_cat.php'><h1><img src="img/Logo.png"></h1></a></center>
                <center><a href='show_child_cat.php?cat_id=2'><h1><img src="img/Math.png"></h1></a></center>
                <center><a href='show_child_cat.php?cat_id=6'><h1><img src="img/Computer Science.png"></h1></a></center>
            </div>
        </div> 

        <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>
            <h4> &#10084  Looking For Something Specific?  &#10084 </h4>
            <label>Enter Keywords:</label>
            <form method = 'post' action = 'search_submit.php'>
            <input type = 'text' name ='search_field'>
            
 <div class="row">
            <div class="large-12 columns">
              <label>Search in:</label>
              <select name=search_type>
                <option value="CategoriesOption">Categories</option>
                <option value="DiscussionsOption">Discussions</option>
                <option value="CommentsOption">Comments</option>
                <option value="AllOption">All</option>
              </select>
            </div>
          </div>
<input type = 'submit' name = 'submit2' class='button' value = 'Search'>
            </form>
        </div>
</div>

<div class="row">
        <div class="large-11 large-centered columns panel text-center medium-11 medium-centered small-11 small-centered">
                <h3 class='text-center'>Welcome to Quadcore Forum! </h3>
                <h4 class='text-center'>Some Interesting Information for all to see! </h4>
        </div>
</div>

<?php

    if(isset($_SESSION['valid_user'])){
    
        $user_type = 0;

        if(!($db = db_connect())){
            echo "Database error<br>";
            exit;
        } 

        $username = input_clean($_SESSION['valid_user']);
        $query = 'select user_id, user_type from user where user_name=?';
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userid, $user_type);
        $stmt->fetch();
        $stmt->close();
        
        if(isset($_POST['submit'])){
            $anamestat = true;
            $atextstat = true;
            $aradstat = true;

            if(!isset($_POST['aname']) || empty($_POST['aname']))
                $anamestat = false;
            else{
                $aname = input_clean($_POST['aname']);
            }
            
            if(!isset($_POST['atext']) || empty($_POST['atext']))
                $atextstat = false;
            else{
                $atext = input_clean($_POST['atext']);
            }

            if($anamestat && $atextstat && $aradstat){
                
                if(!($db = db_connect())){
                    echo "Database error<br>";
                    exit;
                }

                $arad = input_clean($_POST['arad']);

                mysqli_real_escape_string($db, $aname);
                mysqli_real_escape_string($db, $atext);
                mysqli_real_escape_string($db, $arad);
                
                $query = 'insert into announcement (ann_name, ann_text, viewer) values (?, ?, ?)';
                $stmt = $db->prepare($query);
                $stmt->bind_param('ssi', $aname, $atext, $arad);
                $stmt->execute();
                $stmt->close();

                $ann_id = mysqli_insert_id($db);
                $user_an_Insert = "insert into admin_create_ann (user_id, ann_id, ann_date) values (?, ?, '".date('Y-m-d H:i:s')."')";
                $stmt = $db->prepare($user_an_Insert);
                $stmt->bind_param('ii', $userid, $ann_id);
                $stmt->execute();
                $stmt->close();
                $db->close();  


            } 
        }

    
    }

    if($user_type == 2){
?>
<div class = "row">
     <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>
        <h3 style='color: #008cbb'>Add Announcement</h3> 
        <input type='button' id='addAnc' value='Expand' class='button'>

        <div id='addAncSection'>
        <form method='post' action='index.php'>
        <div class='large-6  medium-6 small-10 columns small-centered'>
            <input type='text' id='aname' name='aname' required maxlength='20' value="<?echo 'Announcement Title'?>"/>
        </div>
        <div class='large-6  medium-6 small-10 columns small-centered'>
            <textarea maxlength='1000' style='height: 200px' name='atext' id='atext'><? echo 'Announcement Text'?></textarea>
        </div>
        <div class='large-6  medium-6 small-10 columns small-centered'>
            <h5 style='color: #008cbb'>Who can see it?</h5> <hr> 
        <h6><input type='radio' name='arad' id='arad' value='0' checked/> Everyone</h6> <hr> 
        <h6><input type='radio' name='arad' id='arad' value='1' /> Administrators and Moderators</h6> <hr> 
        <h6><input type='radio' name='arad' id='arad' value='2' /> Administrators Only </h6> <hr>
        </div>
        <div class='large-6  medium-6 small-10 columns small-centered'>
           <input type='submit' id='submit' name='submit' class='button' value='Submit'/>  
        </div>
    </form>
</div>
</div>
</div>


<?php
    }
?>

<div class = "row">
     <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>

<?php
    
    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }

    $query = 'select ann_id, ann_name, ann_text, viewer from announcement ORDER BY ann_id desc';
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($ann_id, $ann_name, $ann_text, $viewer);
    
    echo "<h3 style='color: #008cbb' >Announcements: </h3>";
    $i = 0;    
    
    while($i < 5) {
        if($stmt->fetch()) { 
            if($user_type >= $viewer){
                echo " <div class='text-left'> ";
                echo "<hr>";
                echo " <h4> $ann_name </h4>";
                echo " <h5> &nbsp &nbsp &nbsp &nbsp $ann_text </h5> ";
                echo "</div> ";
                $i++;
            }
        }
    }

    $stmt->close();

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
