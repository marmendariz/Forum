<?php
/*show_parent_cat.php*/
if(empty($_SERVER["HTTPS"]) ||  $_SERVER["HTTPS"] != "on"){
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <link rel='icon' type='image/x-icon' href='img/Q.gif'>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Account Creation</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src='js/jquery-1.12.0.min.js' type='text/javascript'></script>
  </head>
  <body>

<?php
ini_set('session.save_path','tmp');
session_start();
include_once 'header.php'; 
include_once 'lib.php';
?>
<!---------------------- SHOW PARENT CATEGORIES------------------------------>
<div class='row'>
<div class='large-9 large-centered columns panel medium-9 medium-centered small-10 small-centered'>
  <!-------------------------------------------->
<?php
if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}


if(null == ($parent_cat = filter_input(INPUT_GET, cat_id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) ) || $_GET['cat_id']=='1'){
    echo 'Error. Invalid category ID<br>';
    exit;
}

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
    $parent_cat = input_clean($_GET['cat_id']);
    $query2 = 'select * from cat_cont_dis AS c, discussion AS d where c.cat_id = ? AND c.dis_id = d.dis_id';
    $stmt = $db->prepare($query2);
    if($stmt){
        $stmt->bind_param('i',$parent_cat);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($cat_id, $dis_id1, $dis_id2, $dis_name, $dis_text, $dis_flag, $upvote_count, $downvote_count);

        while($stmt->fetch()){
            echo "<h1>Discussions<h1>";
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
$db->close();


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
