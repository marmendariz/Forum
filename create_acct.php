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
    <script src='js/jquery-1.12.0.min.js' type='text/javascript'></script>
<style>
</style>

  </head>
  <body>
        <?php include 'header.php'; ?>
    <br>
<!--------------------------CREATE ACCOUNT FORM------------------------------>
<div class='row'>
<div class='large-7 large-centered columns panel medium-7 medium-centered small-10 small-centered'>
  <!----------------------------------------->
  <form method='post' action='create_acct.php'>
        
    <div class="row">
      <div class='large-8 columns large-centered text-center medium-8 medium-centered'>
      <h5>Create an Account. You know the drill.</h5>
      </div>
    </div>

    <div class='row'>
        <!-- <div class='large-6 columns large-centered medium-6 medium-centered'> -->
        <div class='large-6 columns medium-6'>
            <label for='username'><b>Username:</b></label>
            <input type="text" id = 'username' placeholder="" name='username'/>
        </div>
        <div class='large-6 columns'>
            <p id='username_msg'>Choose a username</p>
        </div>
    </div>
    
    <div class='row'>
      <!--<div class='large-6 columns large-centered medium-6 medium-centered'>-->
      <div class='large-6 columns medium-6 small-8'>
        <label for='password'><b>Password:</b></label>
        <input type="password" id = 'password' placeholder="" name='password'/>
      </div> 
      <div class='large-6 columns'>
            <p id='password_msg'>Choose a password</p>
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
        $(document).ready(function(){
            //$('#username_msg').hide();
            //$('#username');

            /*
            $('#username').on('keyup',function(){
                var txt = $('#username').val();
                //alert('a');
            $.post('check_username.php', { username: txt },
                function(result){
                    alert('a');
                    $('#username_msg').html(result).show();
                });
        });*/

            $('#username').on('keyup', function(){
                var txt = $('#username').val();
                $.post('check_username.php', {username: txt},
                    function(result){
                        $('#username_msg').html(result).show();                
                    });
            });

            $('#password').on('keyup', function(){
                var txt = $('#password').val();
                $.post('check_password.php', {password: txt},
                    function(result){
                        $('#password_msg').html(result).show();                
                    });
            });
        });
    </script>
  </body>
</html>
