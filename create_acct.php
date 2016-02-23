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
  </head>
  <body>

<?php 

include_once 'header.php'; 

$fnStat = true;
$mnStat = true;
$lnStat = true;
$emStat = true;
//$pnStat = false;
$unStat = true;
$pwStat = true;

if(isset($_POST['submit'])){
/*first Name*/
if(!isset($_POST['fname']) || empty($_POST['fname'])){
    $fnStat = false;
}
else{
    //Check firstName and store it
}
/***********/
/*Middle Name (optional)*/
if(!isset($_POST['mname']) || empty($_POST['mname'])){
    $mnStat = false;
}
else{
    //Check middleName and store it
}
/***********/
/*last Name*/
if(!isset($_POST['lname']) || empty($_POST['lname'])){
    $lnStat = false;
}
else{
    //Check lastName and store it
}
/**********/
/*Email*/
if(!isset($_POST['email']) || empty($_POST['email'])){
    $emStat = false;
}
else{
    //Check userName and store it
}
/**********/
}
?>
<!--------------------------CREATE ACCOUNT FORM------------------------------>
<div class='row'>
<div class='large-7 large-centered columns panel medium-7 medium-centered small-10 small-centered'>
  <!-------------------------------------------->
  <form method='post' action='create_acct.php'>
        
    <div class="row">
      <div class='large-8 columns large-centered text-center medium-8 medium-centered'>
      <h5>Create an account. You know the drill.</h5>
      </div>
    </div>
    <hr>

<!---------------------- FIRST NAME ------------------------------>
    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='fname'><b>First Name</b></label>
            <input type='text' id='fname' name='fname'>
        </div>
<?php
        echo "<div class='large-6 columns'>";
        echo "<br class='show-for-large-up'>";
if(!$fnStat)
        echo "<p id='fname_stat' class='error_txt'>First name invalid";
else
    echo "<p id='fname_stat'>";
         echo '</p></div>';
?>
    </div>
<!------------------------------------------------------------------------>

<!-------------------------- MIDDLE NAME ---------------------------------->
    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='mname'><b>Middle Name (Optional)</b></label>
            <input type='text' id='mname' name='mname'>
        </div>
<?php
        echo "<div class='large-6 columns'>";
        echo "<br class='show-for-large-up'>";
if(!$mnStat)
        echo "<p id='fname_stat' class='error_txt'>Middle name invalid";
else
    echo "<p id='mname_stat'>";
         echo '</p></div>';
?>
    </div>
<!------------------------------------------------------------------------>

<!-------------------------- LAST NAME  ---------------------------------->
    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='lname'><b>Last Name</b></label>
            <input type='text' id='lname' name='lname'>
        </div>
<?php
        echo "<div class='large-6 columns'>";
        echo "<br class='show-for-large-up'>";
if(!$lnStat)
        echo "<p id='fname_stat' class='error_txt'>Last name invalid";
else
    echo "<p id='fname_stat'>";
         echo '</p></div>';
?>
    </div>
<!------------------------------------------------------------------------>

<!------------------------------ EMAIL  ---------------------------------->
    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='email'><b>Email</b></label>
            <input type='email' id='email' name='email'>
        </div>
<?php
        echo "<div class='large-6 columns'>";
        echo "<br class='show-for-large-up'>";
if(!$emStat)
        echo "<p id='fname_stat' class='error_txt'>Email invalid";
else
    echo "<p id='fname_stat'>";
         echo '</p></div>';
?>
    </div>
<!-------------------------------------------------------------------->

<!-------------------------- PHONE NUMBER ---------------------------------->
<!--   <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='phone'><b>Phone Number (Optional)</b></label>
            <input type='text' id='phone' name='phone'>
        </div>
    </div>
   --> 
<!---------------------------------------------------------------------------->

<!-------------------------- USERNAME ---------------------------------->
    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='username'><b>Username</b></label>
            <input type="text" id = 'username' placeholder="" name='username'/>
        </div>
<?php
        /*
        echo "<div class='large-6 columns'>";
        echo "<br class='show-for-large-up'>";
if(!$unStat)
        echo "<p id='fname_stat' class='error_txt'>First name invalid";
else
    echo "<p id='username_stat'>";
        echo '</p></div>';
         */
?>
        <div class='large-6 columns'>
            <p id='username_stat'>Choose a username</p>
        </div>
    </div>
<!------------------------------------------------------------------------>

<!------------------------------ PASSWORD  ------------------------------>
    <div class='row'>
      <div class='large-6 columns medium-6 small-8'>
        <label for='password'><b>Password</b></label>
        <input type="password" id = 'password' placeholder="" name='password'/>
      </div> 
<?php
        /*
        echo "<div class='large-6 columns'>";
        echo "<br class='show-for-large-up'>";
if(!$pwStat)
        echo "<p id='fname_stat' class='error_txt'>First name invalid";
else
    echo "<p id='username_stat'>";
        echo '</p></div>';
         */
?>
      <div class='large-6 columns'>
            <p id='password_stat'>Choose a password</p>
       </div>
    </div>
<!-------------------------------------------------------------------->

    <div class='row'>
      <div class='columns large-4 large-centered medium-6 medium-centered'>
      <input type='submit' name='submit' class='button expand' value='Create Account'>    
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
                        $('#username_stat').html(result).show();                
                    });
            });

            $('#password').on('keyup', function(){
                var txt = $('#password').val();
                $.post('check_password.php', {password: txt},
                    function(result){
                        $('#password_stat').html(result).show();                
                    });
            });
        });
        //$("#phone").mask("(999) 999-9999");

    </script>
  </body>
</html>
