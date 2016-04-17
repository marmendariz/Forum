<?
include_once 'lib.php';
set_path();
force_ssl();
session_start();
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
$login_failed = false;

/****************QUERY DB FOR LOGIN************************/
if(isset($_POST['username']) && isset($_POST['password'])){
    if(!($db = db_connect()))
    {
        echo "Database error<br>";
        exit;
    }
    else{
        $username = mysqli_real_escape_string($db, input_clean($_POST['username']));
        $pwd = mysqli_real_escape_string($db,input_clean($_POST['password']));
        $query = 'select salt from user where user_name=?';
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($salt);
        $stmt->fetch();
        $hashed=crypt($pwd,'$6$'.$salt);

        $query = 'select user_id, selector from user where user_name=? and hashed_pwd=?';
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss',$username, $hashed);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        if($num_rows>0){
            $stmt->bind_result($user_id, $selector);
            $stmt->fetch();
            $_SESSION['valid_user'] = $username;
            $_SESSION['user_id'] = $user_id;

            /******* COOKIE STUFF *********/
            if(isset($_POST['rememberMe'])){
                $rememberMe = input_clean($_POST['rememberMe']);
                if(input_clean($_POST['rememberMe'])=='yes'){
                    $exp = time()+(86400*30);
                    $token = gen_token();
                    setcookie("selector", $selector, $exp);
                    setcookie("token", $token, $exp);
                    $hToken = crypt($token,"$5$");
                    $updateToken = "Update user set token='$hToken' where user_id=$user_id";
                    $st = $db->prepare($updateToken);
                    if(!$st->execute()){
                        echo "<br><br><br>Error";
                        exit;
                    }
                    $st->close();
                }
            }
            /******************************/
        }
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
      <div class='large-8 columns large-centered medium-8 medium-centered'>
        <input type="checkbox" checked id='rememberMe' name='rememberMe' value='yes'/>
        <label for='password'><b>Stay signed in</b></label>
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
