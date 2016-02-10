<?php
if(empty($_SERVER["HTTPS"]) ||  $_SERVER["HTTPS"] != "on")
{
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit();
}
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Account Creation</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
<style>

.large-3{
    
}

</style>

  </head>
  <body>
        <?php
            include 'header.php';
        ?>
    <br>
<!--------------------------CREATE ACCOUNT FORM------------------------------>
<div class='row'>
<div class='large-7 large-centered columns panel medium-7 medium-centered small-10 small-centered'>
  <!----------------------------------------->
  <form method='post' action='login.php'>
        
    <div class="row">
      <div class='large-4 columns large-centered text-center medium-8 medium-centered'>
      <h5>Create an Account. You know the drill.</h5>
      </div>
    </div>
    
    <div class='row'>
      <div class='large-6 columns large-centered medium-6 medium-centered'>
        <label for='username'><b>Username:</b></label>
        <input type="text" id = 'username' placeholder="" name='username'/>
      </div>         
    </div>
    
    <div class='row'>
      <div class='large-6 columns large-centered medium-6 medium-centered'>
        <label for='password'><b>Password:</b></label>
        <input type="password" id = 'password' placeholder="" name='password'/>
      </div>         
    </div>
        
    <div class='row'>
      <div class='columns large-4 large-centered medium-6 medium-centered'>
      <input type='submit' class='button expand' value='Submit'>    
      </div>
    </div>
  </form>
  <!---------------------------------------->
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
