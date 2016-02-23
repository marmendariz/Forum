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

</style>

  </head>
  <body>
        <?php include_once 'header.php'; ?>
<!--------------------------CREATE ACCOUNT FORM------------------------------>
<div class='row'>
<div class='large-7 large-centered columns panel medium-7 medium-centered small-10 small-centered'>
  <!----------------------------------------->
  <form method='post' action='create_acct.php'>
        
    <div class="row">
      <div class='large-8 columns large-centered text-center medium-8 medium-centered'>
      <h5>Create an account. You know the drill.</h5>
      </div>
    </div>
    <hr>

    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='fname'><b>First Name</b></label>
            <input type='text' id='fname' name='fname'>
        </div>
    </div>

    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='mname'><b>Middle Name (Optional)</b></label>
            <input type='text' id='mname' name='mname'>
        </div>
    </div>

    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='lname'><b>Last Name</b></label>
            <input type='text' id='lname' name='lname'>
        </div>
    </div>

    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='email'><b>Email</b></label>
            <input type='email' id='email' name='email'>
        </div>
    </div>

    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='phone'><b>Phone Number (Optional)</b></label>
            <input type='text' id='phone' name='phone'>
        </div>
    </div>

    <div class='row'>
        <!-- <div class='large-6 columns large-centered medium-6 medium-centered'> -->
        <div class='large-6 columns medium-6'>
            <label for='username'><b>Username</b></label>
            <input type="text" id = 'username' placeholder="" name='username'/>
        </div>
        <div class='large-6 columns'>
            <p id='username_msg'>Choose a username</p>
        </div>
    </div>
    
    <div class='row'>
      <!--<div class='large-6 columns large-centered medium-6 medium-centered'>-->
      <div class='large-6 columns medium-6 small-8'>
        <label for='password'><b>Password</b></label>
        <input type="password" id = 'password' placeholder="" name='password'/>
      </div> 
      <div class='large-6 columns'>
            <p id='password_msg'>Choose a password</p>
       </div>
    </div>
        
    <div class='row'>
      <div class='columns large-4 large-centered medium-6 medium-centered'>
      <input type='submit' class='button expand' value='Create Account'>    
      </div>
    </div>
  </form>
  <!---------------------------------------->
  </div>
</div>
<!-------------------------------------------------------------------->    
    <script src="js/vendor/jquery.js"></script>
    <script src="js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script src="js/foundation.min.js"></script>
    <script>
        $(document).foundation();
        $(document).ready(function(){
            //$('#username_msg').hide();
            //$('#username');

            //$('#phone').mask("(999)-999-9999");

    
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
        $("#phone").mask("(999) 999-9999");

    </script>
  </body>
</html>
