<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forum | Login</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
<style>
</style>
</head>
<body>

<?php
session_start();
include 'header.php';
echo '<br>';

/*********************FORCE SSL SECURED CONNECTION********************************/
if(empty($_SERVER["HTTPS"]) ||  $_SERVER["HTTPS"] != "on"){
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
/*********************************************************************************/

/****************QUERY DB FOR LOGIN************************/
if(isset($_POST['username']) && isset($_POST['password'])){
    $username = htmlspecialchars($_POST['username']);
    $pwd = htmlspecialchars($_POST['password']);

    @ $db = new mysqli('localhost','quadcore','Vek,6zum','quadcore');
    if(mysqli_connect_errno()){
        echo "Database error<br>";
        exit;
    } 
    else{
        //echo 'Success<br<';
        $query = 'select * from login where username=? and pwd=?';
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss',$username, $pwd);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        if($num_rows>0)
            $_SESSION['valid_user'] = $username;
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
        <h5>You are already logged in as <?php echo $_SESSION['valid_user'] ?>.</h5>
        <h6>Not you?<a href='#'> Logout.</a></h6>
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
      <input type='submit' class='button expand' value='Submit'>    
      </div>
    </div>
    
    <hr>
    <div class='row'>
      <div class='row'>
        <div class='large-4 columns large-centered text-center'>        
          <h5>New to the Forum?</h5>
        </div>
      </div>
      <div class='large-6 large-centered columns'>
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
