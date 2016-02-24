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
session_start();
include_once 'header.php'; 
include_once 'lib.php';
?>
<!---------------------- SHOW PARENT CATEGORIES------------------------------>
<div class='row'>
<div class='large-7 large-centered columns panel medium-7 medium-centered small-10 small-centered'>
  <!-------------------------------------------->
<?php
if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}
$query = 'select * from category where parent_cat_id=1';
$stmt = $db->prepare($query);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($cat_id, $cat_name, $cat_level, $cat_text, $parent_cat_id);
while($stmt->fetch()){
    echo "<a href='show_child_cat.php?cat_id=$cat_id'><h1>$cat_id $cat_name $cat_text $cat_level</h1></a>";
    echo '<hr>';
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
