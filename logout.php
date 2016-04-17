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
    <title>Quadcore Forum | Logout</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
<style>
</style>
</head>
<body>

<?php
include_once 'header.php';

/**********************************************/
/*IF VALID LOGIN STILL ACTIVE, DISPLAY MESSAGE*/
if(isset($_SESSION['valid_user'])){
    $old_user = $_SESSION['valid_user'];
    unset($_SESSION['valid_user']);
    unset($_SESSION['user_id']);
    setcookie('token', null, time()-3600);
    setcookie('selector', null, time()-3600);
    setcookie('active', null, time()-3600);
    session_destroy();
    /************SHOW LOGGED-OUT MESSAGE******************/
?>
    <div class='row'>
        <div class='large-7 columns panel large-centered text-center'>
            <h5>You have been logged out.</h5>
        </div>
    </div>

<?php 
    }
else
{
    /************SHOW NOT-LOGGED-IN MESSAGE***************/
?>
    <div class='row'>
        <div class='large-7 columns panel large-centered text-center'>
            <h5>You are not currently logged in.</h5>
            <h5><a href='login.php'>Login here.</a></h5>
        </div>
    </div>

<?php } ?>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
