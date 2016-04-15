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
    <title>Quadcore Forum | Discussion Sub-Categories</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src='js/jquery-1.12.0.min.js' type='text/javascript'></script>
  </head>
  <body>

<?php
include_once 'header.php'; 
$discussion_flag = false;
$category_verified = false;

?>
<!---------------------- SHOW PARENT CATEGORIES------------------------------>
<div class='row'>
<div class='large-12 large-centered columns panel medium-9 medium-centered small-10 small-centered'>
  <!-------------------------------------------->
<?php
if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}


if(null == ($parent_cat = filter_input(INPUT_GET, cat_id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) ) || $_GET['cat_id']=='1'){
    echo '<br><br><h4>Error. Invalid category ID</h4>';
    exit;
}

$parent_cat_backup = $parent_cat;

$query = 'select * from category where cat_id=?';
$stmt = $db->prepare($query);
$stmt->bind_param('i', $parent_cat);
$stmt->execute();
$stmt->store_result();
$rows = $stmt->num_rows();
$stmt->bind_result($cat_id_verified, $cat_name_verified, $cat_level_verified, $cat_text_verified, $parent_cat_id_verified);
if ($rows) {
    $stmt->fetch();
    $category_verified = true;
} else {
    echo "<br><<br>h4>Error! Category Does Not Exist!</h4>";
    exit;
}
 

/********** Query **********/

$parent_cat = input_clean($_GET['cat_id']);
$query = 'select * from category where parent_cat_id=?';
$stmt = $db->prepare($query);
$stmt->bind_param('i',$parent_cat);
$stmt->execute();

$stmt->store_result();
$rows = $stmt->num_rows();
$stmt->bind_result($cat_id, $cat_name, $cat_level, $cat_text, $parent_cat_id);


if($rows){
    while($stmt->fetch()){
        echo "<a href='show_child_cat.php?cat_id=$cat_id'><h3 style='color:#008cbb;'>$cat_name</h3>";
        echo "<p>&nbsp &nbsp &nbsp &nbsp$cat_text</p>";
        echo '<hr>';
    }
}
else{
    $discussion_flag = true;
    $parent_cat = input_clean($_GET['cat_id']);
    $query2 = 'select * from cat_cont_dis AS c, discussion AS d where c.cat_id = ? AND c.dis_id = d.dis_id';
    $stmt = $db->prepare($query2);
    if($stmt){
        $stmt->bind_param('i',$parent_cat);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($cat_id, $dis_id1, $dis_id2, $dis_name, $dis_text, $dis_flag, $upvote_count, $downvote_count);

            echo "<h1>Discussions<h1>";
        while($stmt->fetch()){
            echo "<hr>"; 
            echo "<a href='discussion.php?dis_id=$dis_id1'><h3 style='color:#008cbb;'>$dis_name</h3>";
            echo "<p>&nbsp &nbsp &nbsp &nbsp$dis_text</p>";
            echo '<hr>'; 
        }
    }
    else{
        echo "<h1>Error</h1>";
    }
}

$stmt->close(); 

/********** Query info about discussion level ********/

/*
//    $dis_parent = input_clean($_GET['cat_id']);
    $dis_info_query = 'select * from category where cat_id=?';
    $stmt = $db->prepare($dis_info_query);
    $stmt->bind_param('i',$parent_cat);
    $stmt->store_result();
    $stmt->bind_result($dis_parent_id, $dis_parent_name, $dis_parent_level, $dis_parent_text, $dis_parent_parent_cat_id);
    $stmt->fetch();
    $stmt->close();
 */

$db->close();

if (!($discussion_flag)) {

    echo "<a href='create_new.php?cat_level=$cat_level&parent_cat_id=$parent_cat_id'class='small round button'>Create New Category</a><br/>";
} 
if ($discussion_flag) 
    echo"<a href='create_new_discussion.php?cat_id=$parent_cat_backup'class='small round button'>Create New Discussion</a><br/>";
?>

  <!-------------------------------------------->
  </div>
</div>
<!-------------------------------------------------------------------->    
    <script src="js/vendor/jquery.js"></script>
    <script src="js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script src="js/foundation.min.js"></script>
    <script>
        $(document).foundation();
        $(document).ready(function(){
        });
    </script>
  </body>
</html>
