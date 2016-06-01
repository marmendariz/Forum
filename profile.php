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
    <link rel='icon' type='image/x-icon' href='img/Q.png' /> 
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Profile</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>

<?php
include_once 'header.php';

$login_failed = false;

/**********************************************/
/*IF VALID LOGIN STILL ACTIVE, DISPLAY MESSAGE*/
if(isset($_SESSION['valid_user'])){
?>
<div class='row'>
    <!---<div class='large-7 columns panel large-centered text-center medium-7 medium-centered small-10 small-centered'>-->
<?php
    

    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }

    $username = input_clean($_SESSION['valid_user']);
    $query = 'select profile_image,user_type, ban_flag, f_name, m_name, l_name, bio, email, date_joined, com_count, dis_count, upvote_count, downvote_count from user where user_name=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($profile_image,$user_type, $ban_flag, $f_name, $m_name, $l_name, $bio, $email, $date_joined, $com_count, $dis_count, $up_count, $down_count);
    $stmt->fetch();
    
    //echo "  <h5>You are logged in as $username.</h5>";
    //echo "  <h6>Not you?<a href='logout.php'> Logout.</a></h6>";
    //echo "</div>";
    echo "<div class='columns panel text-center large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "  <h3 style='color: #008cbb' >Username: </h3> <h4> $username</h4>";
    echo "</div>";
    echo "<div class='columns panel text-center  large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "  <h3 style='color: #008cbb'> Name: </h3> <h4>$f_name $m_name $l_name<h4/>";
    echo "</div>";
    echo "<div class='columns panel text-center  large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "  <h3 style='color: #008cbb'> User Status: </h3> <h4>";
    if($user_type == 0)
        echo "Standard User";
    else if($user_type == 1)
        echo "Moderator";
    else if($user_type == 2)
        echo "Administrator";
    else
        echo "No Valid User Type Found";
    
    echo "</h4>";
    echo "</div>";
    echo "<div class='columns panel text-center  large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "  <h3 style='color: #008cbb' >Email: <h3/> <h4>$email</h4>";
    echo "</div>";
    echo "<div class='columns panel text-center  large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "  <h3 style='color: #008cbb' >Profile Picture: <h3/>";
    echo "<img src='$profile_image'>";
    echo "</div>";
    
    echo "<div class='columns panel text-center  large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "  <h3 style='color: #008cbb' >Bio: <h3/> <h6>$bio</h6>";
    echo "</div>";
    echo "<div class='columns panel text-center  large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "  <h3 style='color: #008cbb' >Statistics: </h3>";
    echo "  <div class='text-left'>";
    echo "      <h6>Date Joined: $date_joined </h6>";
    echo "      <h6>Comment Count: $com_count </h6>";
    echo "      <h6>Discussion Count: $dis_count </h6>";
    echo "      <h6>Total Upvotes: $up_count </h6>";
    echo "      <h6>Total Downvotes: $down_count </h6>";
    echo "  </div>";
    echo "</div>";
    echo "<div class='columns panel text-center large-8 large-centered medium-8 medium-centered small-10 small-centered '>";
    echo "  <h6> Want to change something? No worries.</h6>";
    echo "  <hr> <a href='profile_edit.php'><input type='button' class='button' value='Edit Account'></a>";

?>

</div>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
<?php
    exit;
}
else { 
?>
<div class='row'>
    <div class='large-7 columns panel large-centered text-center'>
        <h5>You must be logged in in order to view your profile!</h5>
        <h6>You can log in <a href='login.php'>here! </a></h6>
    </div>
</div>
<!-------------------------------------------------------------------->    
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
<?php
    exit;
}
?>
