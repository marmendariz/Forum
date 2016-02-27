<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Profile</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>

<?php
session_start();
include_once 'header.php';
include_once 'lib.php';

$login_failed = false;

/*********************FORCE SSL SECURED CONNECTION********************************/
if(empty($_SERVER["HTTPS"]) ||  $_SERVER["HTTPS"] != "on"){
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
/*********************************************************************************/

/**********************************************/
/*IF VALID LOGIN STILL ACTIVE, DISPLAY MESSAGE*/
if(isset($_SESSION['valid_user'])){
?>
<div class='row'>
    <div class='large-7 columns panel large-centered text-center'>
        <h5>You are logged in as <?php echo $_SESSION['valid_user'] ?>.</h5>
        <h6>Not you?<a href='logout.php'> Logout.</a></h6>
    </div>
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
