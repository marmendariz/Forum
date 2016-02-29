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
ini_set('session.save_path','/tmp');
session_start();
include_once 'header.php';

/*********************FORCE SSL SECURED CONNECTION********************************/
if(empty($_SERVER["HTTPS"]) ||  $_SERVER["HTTPS"] != "on"){
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
/*********************************************************************************/

/**********************************************/
/*IF VALID LOGIN STILL ACTIVE, DISPLAY MESSAGE*/
if(isset($_SESSION['valid_user'])){
    $old_user = $_SESSION['valid_user'];
    unset($_SESSION['valid_user']);
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
