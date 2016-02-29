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
    <link rel='icon' type='image/x-icon' href='img/Q.png'>
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
ini_set('sesson.save_path','tmp');
session_start();
include_once 'header.php'; 
include_once 'lib.php';
?>
<!---------------------- DISCUSSION PAGE------------------------------>
<div class='row'>
<div class='large-7 large-centered columns panel medium-7 medium-centered small-10 small-centered'>
  <!-------------------------------------------->
<?php
if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}

if (null == ($parent_dis = filter_input(INPUT_GET, dis_id, FILTER_VALIDATE_INT,FILTER_NULL_ON_FAILURE) ) || $_GET['dis_id'] == '0') {
    echo 'Error. Invalid Discussion ID<br>';
}

$parent_dis = input_clean($_GET['dis_id']);
$dis_query = 'select dis_name, dis_text from discussion where dis_id = ?';
$query = 'select * from dis_cont_com AS d, com AS c WHERE d.dis_id=? AND d.com_id=c.com_id';
$stmt = $db->prepare($query);
$stmt->bind_param('i', $parent_dis);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($dis_id, $com_id1, $com_id2,$com_name, $com_level, $com_text, $com_flag, $parent_com_id, $upvote_count, $downvote_count);

$dis_stmt = $db->prepare($dis_query);
$dis_stmt->bind_param('i',$parent_dis);
$dis_stmt->execute();
$dis_stmt->store_result();
$dis_stmt->bind_result($dis_name, $dis_text);
$dis_stmt->fetch();
   echo "<h1>$dis_name<h1><hr>";  
   echo "<h3>$dis_text<h3><hr>";  
   echo "<h4>Comments<h4><hr>";  
while($stmt->fetch()){
    echo "<h5 style='color:#008cbb;'>$com_name<h5>";
    echo "<p>&nbsp &nbsp &nbsp &nbsp$com_text</p>"; 
    echo '<hr>';
}

$dis_stmt->close();
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
