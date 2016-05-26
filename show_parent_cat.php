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
<div class='large-12 large-centered columns panel medium-9 medium-centered small-10 small-centered'>

<div class='row'>
    <div class='large-12 large-centered columns medium-9 medium-centered small-10 small-centered'>
        <h2>Forums</h2>
    </div>
</div>

<hr>

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

$query = 'select * from category where cat_level=2';
$stmt = $db->prepare($query);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($cat_id, $cat_name, $cat_level, $cat_text, $parent_cat_id);
while($stmt->fetch()){
        echo "<hr>";
        echo "<div class='row'>";
        echo "<div class ='small-6 medium-6 large-6 columns'>";
        echo "<a href='show_child_cat.php?cat_id=$cat_id'><h3 style='color:#008cbb;'>$cat_name</h3></a>";
        echo "<p>&nbsp &nbsp &nbsp &nbsp".stripslashes($cat_text)."</p>";
        echo "</div>";

        echo "<div class = 'small-6 medium-6 large-6 columns text-right'><br>";
        //if($logged_in && $user_type == 2){         
            echo "<a href='create_new.php?parent_cat_id=$cat_id'class='small round button'>Create New SubCategory</a><br/>";
        //}    
        echo "</div>"; 
        echo "</div>"; 

}

$stmt->close();
$db->close();


if($logged_in){
    
    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }   

    $query = 'select user_type from user where user_name=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_type);
    $stmt->fetch();
    
    if($user_type == 2){
      echo"<a href='create_new.php?parent_cat_id=1'class='small round button'>Create New Category</a><br/>";
    }

$stmt->close();
$db->close();
}
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
