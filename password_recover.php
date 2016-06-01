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
    <title>Quadcore Forum | Login</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>

<?php
include_once 'header.php';
$password_update = false;

/****************QUERY DB FOR LOGIN************************/
/*if(!isset($_GET['hash']) || empty($_GET['hash']))
{
    die("<br><br><br>Bad Request");
}*/

if(isset($_POST['new_password']) && isset($_POST['password'])){
    $passedhash = $_POST['hash'];
    $userid = 0;
    if(!($db = db_connect()))
    {
        echo "Database error<br>";
        exit;
    }
    $query = 'select user_id from reset_password where hash = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $passedhash);
   // echo "<br><br><br> $passedhash";
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userid);
    $stmt->fetch();
    if($userid == 0){
        header("Location: https://www.cs.csubak.edu/~quadcore/Forum/");
    }

    //echo "<br><br><br> $userid";
    if($userid != 0){

        //$username = mysqli_real_escape_string($db, input_clean($_POST['username']));
        $pwd = mysqli_real_escape_string($db,input_clean($_POST['password']));
        $query = 'Update user set hashed_pwd = ?, salt = ? where user_id = ?';
        //$query2 = 'Update salt=? from user where user_name = ?';
        $fp = fopen('/dev/urandom','r');
        $random = fread($fp,32);
        fclose($fp);
        $salt = base64_encode($random);
        $hashed = crypt($pwd, '$6$'.$salt);
        $salt = mysqli_real_escape_string($db,$salt);
        $hashed = mysqli_real_escape_string($db,$hashed);
        //echo "$username ** $pwd ** $hashed ** $salt";
        $stmt = $db->prepare($query);
        $stmt->bind_param('sss', $hashed,$salt,$userid);
        if(!$stmt->execute()){
            echo 'Failure to save to database';
            $stmt->close();
            exit();

            $stmt->close();
        }
        $db->close();
        header("Location: login.php");
    }

}   

else if(!isset($_GET['hash']) || empty($_GET['hash']))
{
        die("<br><br><br>Bad Request");
}



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
?>
<!--------------------------LOGIN FORM------------------------------>
<div class='row'>
<div class='large-7 large-centered columns panel medium-7 medium-centered small-10 small-centered'>
  <!----------------------------------------->
  <form method='post' action='password_recover.php'>

    <div class="row">
      <div class='large-3 columns large-centered text-center'>
      <h5>Reset Password</h5>
      </div>
    </div>
<!--
   <div class='row'>
      <div class='large-8 columns large-centered medium-8 medium-centered'>
        <label for='username'><b>User Name:</b></label>
        <input type="text" id = 'username' placeholder="" name='username'/>
      </div>         
    </div>
-->
<? echo "<input type='hidden' name='hash' value='".$_GET['hash']."'>";?>
    <div class='row'>
      <div class='large-8 columns large-centered medium-8 medium-centered'>
        <label for='new_password'><b>New Password:</b></label>
        <input type="text" id = 'new_password' placeholder="" name='new_password'/>
      </div>         
    </div>

    <div class='row'>
      <div class='large-8 columns large-centered medium-8 medium-centered'>
        <label for='password'><b>Repeat Password:</b></label>
        <input type="password" id = 'password' placeholder="" name='password'/>
      </div>         
    </div>



    <div class='row'>
      <div class='columns large-6 large-centered medium-6 medium-centered small-12 small-centered'>

     <input type='submit' class='button expand' value='Submit'>    

   </div>
    </div>

<?php
if($password_update){
    echo "
        <div class='row'>
        <div class='columns large-4 large-centered text-center'>
        <h5 id='login_error'>Passsword Reset Failed</h5>
        </div>
        </div>
        ";
}
?>

  </form>
  <!---------------------------------------->
  </div>
</div>
<!-------------------------------------------------------------------->    
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
<script>
$(document).foundation();

$('#rememberMeLabel').on('click', function(){
    var checkBox = $('#rememberMe');
    var isChecked = checkBox.is(':checked'); 
    if(isChecked)
        checkBox.prop('checked', false);
    else
        checkBox.prop('checked', true);
});


</script>
  </body>
</html>
