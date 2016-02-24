<!doctype html>
<html class="no-js" lang="en">
  <head>
    <link rel='icon' type='image/x-icon' href='img/Q.gif'>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Login</title>
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

/****************QUERY DB FOR LOGIN************************/
if(isset($_POST['username']) && isset($_POST['password'])){

    @ $db = new mysqli('localhost','quadcore','Vek,6zum','quadcore');
    if(mysqli_connect_errno()){
        echo "Database error<br>";
        exit;
    } 
    else{
        $username = mysqli_real_escape_string($db, input_clean($_POST['username']));
        $pwd = mysqli_real_escape_string($db,input_clean($_POST['password']));
        $query = 'select * from login where username=? and password=?';
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss',$username, $pwd);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        if($num_rows>0)
            $_SESSION['valid_user'] = $username;
        else
            $login_failed = true;
        $stmt->close();
    }
    $db->close();
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
  <form method='post' action='login.php'>
        
    <div class="row">
      <div class='large-3 columns large-centered text-center'>
      <h5>Please Login</h5>
      </div>
    </div>
    
    <div class='row'>
      <div class='large-8 columns large-centered medium-8 medium-centered'>
        <label for='username'><b>Username:</b></label>
        <input type="text" id = 'username' placeholder="" name='username'/>
      </div>         
    </div>
    
    <div class='row'>
      <div class='large-8 columns large-centered medium-8 medium-centered'>
        <label for='password'><b>Password:</b></label>
        <input type="password" id = 'password' placeholder="" name='password'/>
      </div>         
    </div>
        
    <div class='row'>
      <div class='columns large-6 large-centered medium-6 medium-centered small-12 small-centered'>
      <input type='submit' class='button expand' value='Submit'>    
      </div>
    </div>

<?php
if($login_failed){
    echo "
    <div class='row'>
        <div class='columns large-4 large-centered text-center'>
            <h5 id='login_error'>Login Failed</h5>
        </div>
    </div>
    ";
    }
?>

    <hr>
    <div class='row'>
      <div class='row'>
        <div class='large-4 columns large-centered text-center'>        
          <h5>New to the Forum?</h5>
        </div>
      </div>
      <div class='large-8 large-centered columns medium-8 medium-centered'>
        <a href="create_acct.php"><input type='button' class='button expand' value='Create an Account'></a>
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
