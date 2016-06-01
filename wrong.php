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
?>
    <div class='row'>
        <div class='large-7 columns panel large-centered text-center'>
            <h5> Username and Email did not match.</h5>
            <h5><a href='email_password.php'>Try Again Here.</a></h5>
        </div>
    </div>


<?php  ?>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
