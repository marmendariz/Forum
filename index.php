<?
    include_once 'lib.php';
    set_path();
    force_ssl();
    session_start();
    auto_login();

    function draw_bar_graph($width, $height, $data, $max_value, $filename) {
        // create the empty graph image
        $img = imagecreatetruecolor ($width, $height);
        // set background with black text and grey graphics
        $bg_color = imagecolorallocate($img, 255, 255, 255);
        $text_color = imagecolorallocate($img, 255, 255, 255);
        $bar_color = imagecolorallocate($img, 0, 0, 0);
        $border_color = imagecolorallocate($img, 192, 192, 192);

        // fill the background
        imagefilledrectangle($img, 0, 0, $width, $height, $bg_color);

        // draw the bars
        $bar_width = $width / ((count($data) * 2) + 1);

        for ($i=0; $i<count($data); $i++) {
            imagefilledrectangle($img, ($i * $bar_width * 2) + $bar_width, $height, 
                ($i * $bar_width * 2) + ($bar_width * 2) , $height - (($height/ $max_value) * $data[$i][1]), $bar_color);
            imagestringup ($img, 5, ($i * $bar_width * 2) + ($bar_width), $height-5, $data[$i][0], $text_color);
        }
            // draw a rectangle around the whole thing
        imagerectangle($img, 0, 0, $width-1, $height-1, $border_color);
        
            //draw a range up the left side of the graph
            //imagestring($img, 5,0,$height - ($i * ($height/ $max_value)), $i, $bar_color);
        
        // write the graph image to a file
        imagepng ($img, $filename, 5);
    }

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
        $loggedin = true; 
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

<?php if($loggedin){?>

<div class = "row">
     <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>
<?php
   
    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }
    
        echo "<h3 style='color: #008cbb' >Recent Bookmarks:</h3>";

        $username = input_clean($_SESSION['valid_user']);
        $query = 'select user_id, user_type from user where user_name=?';
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userid, $user_type);
        $stmt->fetch();
        $stmt->close();

        $query = 'select d.dis_id, d.dis_name, d.dis_text 
                  from user u, bookmarked b, discussion d  
                  where u.user_id=? and u.user_id=b.user_id 
                  and b.dis_id=d.dis_id Order By b.date desc';

        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $userid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($b_dis_id, $b_dis_name, $b_dis_text);

        $i = 0;

        while($i < 5 && $stmt->fetch()){ 
                echo " <div class='text-left'> ";
                echo "<hr>";
                echo "<a href='discussion.php?dis_id=$b_dis_id'> <h4 style='color: #008cbb'> $b_dis_name </h4></a>";
                echo " <h5> &nbsp &nbsp &nbsp &nbsp $b_dis_text </h5> ";
                echo "</div> ";
                $i++;
        }
        
        /***************************** Discussion Ratings ****************************/
     echo "</div>";
     echo "<div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>";
        echo "<h3 style='color: #008cbb' >Most Popular Discussions:</h3>";

        $query = 'SELECT dis_id, dis_name, dis_text, Dis_Rating FROM dis_rating NATURAL JOIN discussion ORDER BY Dis_Rating desc';
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($disid, $disname, $distext, $disrating);
 
        $i = 0;

        while($i < 2 && $stmt->fetch()){ 
                echo " <div class='text-left'> ";
                echo "<hr>";
                echo "<a href='discussion.php?dis_id=$disid'> <h4 style='color: #008cbb'> $disname </h4></a>";
                echo " <h5> &nbsp &nbsp &nbsp &nbsp $distext </h5> ";
                echo "</div> ";
                $i++;
        }

        /*************** Make Bar Graph **************/
        $dis_names = array();
        $dis_ratings = array();
        $graph_array = array(array());

        $query = 'SELECT dis_id, dis_name, dis_text, Dis_Rating FROM dis_rating NATURAL JOIN discussion ORDER BY Dis_Rating desc';
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($disid, $disname, $distext, $disrating);
 
        $i = 0;

        while($i < 5 && $stmt->fetch()){ 
            //$dis_names[] = $disname;
            //$dis_ratings[] = $disrating;
            $graph_array[$i][0] = $disname; 
            $graph_array[$i][1] = $disrating; 
                $i++;
        }
        
        /*$graph_array[0][0] = "value1";
        $graph_array[0][1] = 2;
        $graph_array[1][0] = "value2";
        $graph_array[1][1] = 4;*/
echo"<br>";
        draw_bar_graph(480, 240, $graph_array, 40, "img/graph_pic.png");
        echo "<img src= 'img/graph_pic.png'/>";
?>
    </div>
</div>

<?php } ?>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <!---<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
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
