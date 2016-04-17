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
    <title>Quadcore Forum | Discussion Categories</title>


    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src='js/jquery-1.12.0.min.js' type='text/javascript'></script>
  </head>
  <body>

<?php
include_once 'header.php'; 
?>
<!---------------------- SHOW PARENT CATEGORIES------------------------------>
<div class='row'>
<div class='large-12 large-centered columns panel medium-7 medium-centered small-10 small-centered'>
  <!-------------------------------------------->
<?php
if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}
$query = 'select * from category where cat_level=2';
$stmt = $db->prepare($query);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($cat_id, $cat_name, $cat_level, $cat_text, $parent_cat_id);
while($stmt->fetch()){
    //$text = wordwrap($cat_text, 70, "<br>");
    echo "<a href='show_child_cat.php?cat_id=$cat_id'><h3 style='color:#008cbb;'>$cat_name<h3></a>";
    echo "<p>&nbsp &nbsp &nbsp &nbsp$cat_text</p>"; 
    echo '<hr>';
}

$stmt->close();
$db->close();

      echo"<a href='create_new.php?parent_cat_id=1'class='small round button'>Create New Category</a><br/>";
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
