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

if(null == ($parent_cat = filter_input(INPUT_GET, cat_id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) ) || $_GET['cat_id']=='1'){
    echo '<br><br><h4>Error! Invalid category ID - Must be Numeric!</h4>';
    exit;
}

if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}

// This is a $_GET variable
$parent_cat_backup = $parent_cat;

/************ Make sure that the Category Exists ************/

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
    echo "<br><br><h4>Error! Category Does Not Exist!</h4>";
    exit;
}
 
/*Navigation queries*/
$nav_items = array();
$nav_ids = array();
$nav = "select cat_name, parent_cat_id from category where cat_id = ?";
$stmt = $db->prepare($nav);
$stmt->bind_param('i', $cat_id_verified);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($name, $id);
$stmt->fetch();
$nav_ids[] = $cat_id_verified;
$nav_items[] = $name;
while($name!=='Quadcore - Main Category'){
    $nav = "select cat_name, parent_cat_id from category where cat_id = ?";
    $stmt = $db->prepare($nav);
    $nav_ids[] = $id;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name, $id);
    $stmt->fetch();
    $nav_items[] = $name;
}
/****************/

?>
<!---------------------- SHOW PARENT CATEGORIES------------------------------>
<div class='row'>
<div class='large-12 large-centered columns medium-9 medium-centered small-10 small-centered'>
  <!-------------------------------------------->
<?php

if(isset($_SESSION['valid_user'])){
    $logged_in = true;
    $username = input_clean($_SESSION['valid_user']);
}


    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }   

if($logged_in){
    $query = 'select user_type from user where user_name=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_type);
    $stmt->fetch();
}
/********** Query **********/

$query = 'select * from category where parent_cat_id=?';
$stmt = $db->prepare($query);
$stmt->bind_param('i',$parent_cat);
$stmt->execute();

$stmt->store_result();
$rows = $stmt->num_rows();
$stmt->bind_result($cat_id, $cat_name, $cat_level, $cat_text, $parent_cat_id);

/**Navigation**/
echo "<div class='panel'>";
echo "<h2><a href='show_parent_cat.php'>Forums </a>&gt; ";
for($i=count($nav_items)-2; $i>=0;$i--){
    if($i!=0){   
        echo " <a href='show_child_cat.php?cat_id=".$nav_ids[$i]."'>".$nav_items[$i]."</a>";
        echo " &gt;";
    }
    else{
        echo " ".$nav_items[$i];
    }
}
echo "</h2><hr>";
echo "<h3 class='text-center'>Categories</h3>";
/****/

if($rows){
    while($stmt->fetch()){
        echo "<hr>";
        echo "<div class='row'>";
        echo "<div class ='small-6 medium-6 large-6 columns'>";
        echo "<a href='show_child_cat.php?cat_id=$cat_id'><h3 style='color:#008cbb;'>$cat_name</h3></a>";
        echo "<p>&nbsp &nbsp &nbsp &nbsp".stripslashes($cat_text)."</p>";
        echo "</div>";

        echo "<div class = 'small-6 medium-6 large-6 columns text-right'><br>";
        if($logged_in && $user_type == 2){         
            echo "<a href='create_new.php?parent_cat_id=$cat_id'class='small round button'>Create New SubCategory</a><br/>";
        }    
        echo "</div>"; 
        echo "</div>"; 
    }
}

if($user_type==2){
    echo "<div class='row'>";
    echo "<div class='large-centered columns large-4 small-centered'>";
echo "<a href='create_new.php?parent_cat_id=$parent_cat_id'class='medium round button'>Create New Category Here!</a><br/>";
echo "</div>";
echo "</div>";
}
echo "</div>";
$stmt->close(); 


/**** Discussion Query ******/

echo "<div class='panel'>";
    $query2 = 'select * from cat_cont_dis AS c, discussion AS d where c.cat_id = ? AND c.dis_id = d.dis_id';
    $stmt = $db->prepare($query2);
    if($stmt){
        $stmt->bind_param('i',$parent_cat_backup);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($cat_id, $dis_id1, $dis_id2, $dis_name, $dis_text, $dis_flag, $upvote_count, $downvote_count);

            echo "<h3 class='text-center'>Discussions</h3>";
        while($stmt->fetch()){
            echo "<hr>";
            echo "<a href='discussion.php?dis_id=$dis_id1'><h3 style='color:#008cbb;'>$dis_name</h3></a>";
            echo "<p>&nbsp &nbsp &nbsp &nbsp".stripslashes($dis_text)."</p>";
        }
    }
    else
        echo "<hr>";
    $stmt->close();
$db->close();

if($logged_in){
    
    echo "<div class='row'>";
    echo "<div class='large-centered columns large-4 small-centered'>";
 if($user_type == 2){
if (!($discussion_flag)) {
// This variable has been retrieved from the database 
    echo "<a href='create_new_discussion.php?cat_id=$parent_cat_id'class='medium round button'>Create New Discussion Here!</a><br/>";
}
}
}


 
if ($discussion_flag){ 
    // parent_cat_id was not set because there wasn't a category who had the passed in value as a parent
    // that's why we have to use the passed in value (stored a backup so if it's tampered with, it still passes original)
    echo"<a href='create_new_discussion.php?cat_id=$parent_cat_backup'class='small round button'>Create New Discussion!</a><br/>";
}
//$stmt->close();
//$db->close();


    echo "</div>";
    echo "</div>";
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
