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

?>

<br><br><br>
<div class="row">
        <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-11 small-centered'>

<h3> Didn't Find What You Were Looking For? </h3>
<form method = 'post' action = 'search_submit.php' id = searchform>
<input type = 'text' name ='search_field' value = <? echo $cat_input_search ?> >
<input type = 'submit' name = 'submit2' class='button' value = 'Search'>
</form>
       
        </div>
</div>

<?

/*********** What type of Search *************/

    $search_type = $_POST['search_type'];
                
/*************** Category ******************/

    if ($search_type == "CategoriesOption" || $search_type == "AllOption") {

    $query = "SELECT * FROM category WHERE cat_name LIKE '%" . $cat_input_search . "%' OR cat_text LIKE '%" . $cat_input_search . "%'";
    
    $stmt = $db->prepare($query);
    //$stmt->bind_param('s', $cat_input_search);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows();
    $stmt->bind_result($cat_id_verified, $cat_name_verified, $cat_level_verified, $cat_text_verified, $parent_cat_id_verified);
    /******/
    //row, panel
    //columns
    if ($rows) {
        $cat_search_executed = true;
        //row 2
        //columns 2

       echo "<h3>The Following Categories Were Found: </h3> <br>";

        //close columns 2
        //close row 2

        //row 3
        //columns 3
        while ($stmt->fetch()) {
            echo "<a href='show_child_cat.php?cat_id=$cat_id_verified'><h3 style='color:#008cbb;'>$cat_name_verified</h3>";
        echo "<p>&nbsp &nbsp &nbsp &nbsp$cat_text_verified</p>";
        echo "<hr>";
        }
        //close columns 3
        //close row 3
    } else {
        echo "<br><br>Sorry, no results were found.";
        exit;
    } 
    //close row
    //close columns
    /*****/

    }
    /***************************************/

/*************** Discussion ******************/

    if ($search_type == "DiscussionsOption" || $search_type == "AllOption") {

    $query = "SELECT * FROM discussion WHERE dis_name LIKE '%" . $cat_input_search . "%' OR dis_text LIKE '%" . $cat_input_search . "%'";
    
    $stmt = $db->prepare($query);
    //$stmt->bind_param('s', $cat_input_search);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows();
    $stmt->bind_result($dis_id_verified, $dis_name_verified, $dis_text_verified, $dis_flag_verified, $dis_upvote_count, $dis_downvote_count);
    if ($rows) {
        echo "<h3>The Following Discussions Were Found:</h3> <br>";
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

</body>
</html>
