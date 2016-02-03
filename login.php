<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forum | Login</title>
    <link rel="stylesheet" href="css/foundation.css" />
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
<!--------------------------LOGIN FORM------------------------------>
<div class='row'>
<div class='large-8 large-centered columns panel medium-8 medium-centered small-10 small-centered'>
  <!----------------------------------------->
  <form method='post' action='login.php'>
        
    <div class="row">
      <div class='large-3 columns large-centered text-center'>
      <h5>Please Login</h5>
      </div>
    </div>
    
    <div class='row'>
      <div class='large-6 columns large-centered'>
        <label for='username'><b>Username:</b></label>
        <input type="text" id = 'username' placeholder="" name='username'/>
      </div>         
    </div>
    
    <div class='row'>
      <div class='large-6 columns large-centered'>
        <label for='password'><b>Password:</b></label>
        <input type="password" id = 'password' placeholder="" name='password'/>
      </div>         
    </div>
        
    <div class='row'>
      <div class='columns large-4 large-centered'>
      <input type='submit' class='button expand'>    
      </div>
    </div>
  </form>
  <!--------------------------------------->
  </div>
</div>
<!------------------------------------------------------------------>    
    
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
