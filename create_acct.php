<?php
if(empty($_SERVER["HTTPS"]) ||  $_SERVER["HTTPS"] != "on"){
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <link rel='icon' type='image/x-icon' href='img/Q.png'>
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
ini_set('session_save_path','tmp');
session_start();
include_once 'header.php'; 
include_once 'lib.php';

$fnStat = true;
$mnStat = true;
$lnStat = true;
$emStat = true;
$unStat = true;
$pwStat = true;

/********IF Form was submitted, check inputs***********/
if(isset($_POST['submit'])){
/******************First Name**************************/
if(!isset($_POST['fname']) || empty($_POST['fname'])){
    $fnStat = false;
}
else{
    $fname = input_clean($_POST['fname']);
    if(!preg_match('/^[a-zA-Z]+$/',$fname))
        $fnStat = false;
}
/*****************************************************/

/***************Middle Name (optional)***************/
if(!isset($_POST['mname']) || empty($_POST['mname'])){
    $mnStat = false;
}
else{
   $mname = input_clean($_POST['mname']);
    if(!preg_match('/^[a-zA-Z]+$/',$mname))
        $mnStat = false;
}
/********************************************************/

/*******************Last Name***************************/
if(!isset($_POST['lname']) || empty($_POST['lname'])){
    $lnStat = false;
}
else{
    $lname = input_clean($_POST['lname']);
    if(!preg_match('/^[a-zA-Z]+$/',$lname))
        $lnStat = false;
}
/*******************************************************/

/*************************Email************************/
if(!isset($_POST['email']) || empty($_POST['email'])){
    $emStat = false;
}
else{
    $email = input_clean($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)===false)
        $emStat = false;
    
}
/*******************************************************/

if(!isset($_POST['username']) || empty($_POST['username'])){
    $unStat = false;
}
else{
    $username = input_clean($_POST['username']);
}

if(!isset($_POST['password']) || empty($_POST['password'])){
    $pwStat = false;
}
else{
    $password = input_clean($_POST['password']);
    if(strlen($password)<5)
        $pwStat = false;
}

/***********If every input is fine, insert into db****************/
if($fnStat && $mnStat && $lnStat && $emStat && $unStat && $pwStat){
    if(!($db = db_connect())){
        echo 'Database error<br>';
        exit;
    }

    $query = 'Insert into user (user_name,user_type, f_name, 
                               m_name, l_name, email, date_joined, 
                               hashed_pwd, salt)
                               values (?,?,?,?,?,?,?,?,?)';
    $stmt = $db->prepare($query);

    $username = mysqli_real_escape_string($db, $username);
    $password = mysqli_real_escape_string($db, $password);

    $fp = fopen('/dev/urandom','r');
    $randomString = fread($fp,32);
    fclose($fp);
    $salt = base64_encode($randomString);
    $hashed = crypt($password,'$6$'.$salt); 
    $salt = mysqli_real_escape_string($db, $salt);
    $hashed = mysqli_real_escape_string($db, $hashed);
    
    $type = 0;
    $date = date('Y-m-d H:i:s');
    $stmt->bind_param('sisssssss',$username, $type, $fname, $mname,
                                  $lname, $email, $date,
                                  $hashed, $salt);
    if(!$stmt->execute()){
        echo 'Error<br>';
        $stmt->close();
        $db->close();
        exit;
    }
    $stmt->close();
    $db->close();
}
/*********************************************************/
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
            <input type='text' id='fname' name='fname' required maxlength="12"/>
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
            <input type='text' id='mname' name='mname' maxlength="12">
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
            <input type='text' id='lname' name='lname' required maxlength="20">
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
            <input type='email' id='email' name='email' required maxlength="30">
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

<!-------------------------- USERNAME ---------------------------------->
    <div class='row'>
        <div class='large-6 columns medium-6'>
            <label for='username'><b>Username</b></label>
            <input type="text" id = 'username' placeholder="" name='username' required maxlength="20"/>
        </div>
<?php
        
        echo "<div class='large-6 columns'>";
        echo "<br class='show-for-large-up'>";
if(!$unStat)
        echo "<p id='username_stat' class='error_txt'>First name invalid";
else
    echo "<p id='username_stat'>Choose a username";
        echo '</p>';
         
?>
        <!--<div class='large-6 columns'>
            <p id='username_stat'>Choose a username</p>
        --></div>
    </div>
<!------------------------------------------------------------------------>

<!------------------------------ PASSWORD  ------------------------------>
    <div class='row'>
      <div class='large-6 columns medium-6'>
        <label for='password'><b>Password</b></label>
        <input type="password" id = 'password' placeholder="Enter at least 5 characters" name='password' required maxlength="15"/>
      </div> 
<?php
        
        echo "<div class='large-6 columns'>";
        echo "<br class='show-for-large-up'>";
if(!$pwStat)
        echo "<p id='fname_stat' class='error_txt'>First name invalid";
else
    echo "<p id='password_stat'>Choose a password";
        echo '</p></div>';
         
?>
      <!--<div class='large-6 columns'>-->
           <!-- <p id='password_stat'>Choose a password</p>-->
       </div>
    <!--</div>-->
<!-------------------------------------------------------------------->

    <div class='row'>
      <div class='columns large-10 large-centered medium-10 medium-centered'>
      <input id='submit' type='submit' name='submit' class='button expand' value='Create Account'>    
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

            /*Username status ajax*/ 
        $('#username').on('keyup', function(){
                 var txt = $('#username').val();
                $.post('check_username.php', {username: txt},
                    function(result){
                        $('#username_stat').html(result).show();
                        var text = $('#username_stat').text();
                        if(text=='Username taken')
                            $('#username_stat').removeClass('success_txt');
                            $('#username_stat').addClass('error_txt');
                        if(text=='Username available!'){
                            $('#username_stat').removeClass('error_txt');
                            $('#username_stat').addClass('success_txt');
                        }
                        if(text=='Choose a username'){
                            $('#username_stat').removeClass('error_txt');
                           $('#username_stat').removeClass('success_txt');
                        }               
                    });
                           });

        /*Password status ajax*/
            $('#password').on('keyup', function(){
                var txt = $('#password').val();
                $.post('check_password.php', {password: txt},
                    function(result){
                        $('#password_stat').html(result).show();
                        var text = $('#password_stat').text();
                        if(text=='Invalid password' || text=='Too short')
                            $('#password_stat').removeClass('success_txt');
                            $('#password_stat').addClass('error_txt');
                        if(text=='Great!'){
                            $('#password_stat').removeClass('error_txt');
                            $('#password_stat').addClass('success_txt');
                        }
                        if(text=='Choose a password'){
                            $('#password_stat').removeClass('error_txt');
                           $('#password_stat').removeClass('success_txt');
                        }
                    });
            });

            /*If username & password AJAX messages aren't good, preventDefault*/
            $("#submit").on("click",function(event){
                var usernameStat = $('#username_stat').text();
                var passwordStat = $('#password_stat').text();
                if(usernameStat !='Username available!')
                    event.preventDefault();
                if(passwordStat != 'Great!')
                    event.preventDefault();
            });
        });
    </script>
  </body>
</html>
