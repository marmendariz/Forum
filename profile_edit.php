<?
include_once 'lib.php';
set_path();
force_ssl();
session_start();
?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <link rel='icon' type='image/x-icon' href='img/Q.png' /> 
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Edit Profile</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>

<?php
include_once 'header.php';
$login_failed = false;

/*****************************************************************************************************************/
/*If valid login still active, allow */
if(isset($_SESSION['valid_user'])){

/*****************If a form was submitted, we are going to check before updating********/
    if(isset($_POST['submit'])){







    }


/*********************************DISPLAY FORM***********************/
if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }

    $username = input_clean($_SESSION['valid_user']);
    $query = 'select user_type, ban_flag, f_name, m_name, 
                l_name, bio, email, date_joined, com_count, 
                dis_count, upvote_count, downvote_count 
                from user where user_name=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_type, 
                        $ban_flag, 
                        $f_name, $m_name, $l_name, 
                        $bio, $email, $date_joined, 
                        $com_count, $dis_count, 
                        $up_count, $down_count);
    $stmt->fetch();

    echo "<div class ='row'>";
    echo "  <div class='columns panel text-center large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "      <h2 style='color: #008cbb'>Editing $username's Profile</h2>";
    echo "  </div>";
    echo "</div>";


    echo "<form method='post' action='profile_edit.php'>";
    
    echo "  <div class ='row'>";
    
/*********************************DISPLAY NAME***********************/
    
    echo "      <div class='columns panel text-left large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "          <h3 style='color: #008cbb'> Name: </h3><br>";
    echo "          <div class='large-6 medium-6 small-10 columns>";
    echo "              <label for='firstname'><b>First Name</b></label>";
    echo "              <input type ='text' id='firstname' name='firstname' value='$f_name' required maxlength='12'/>";
    echo "              <label for='middlename'><b>Middle Name</b></label>";
    echo "              <input type ='text' id='middlename' name='middlename' value='$m_name' required maxlength='12'/>";
    echo "              <label for='lastname'><b>Last Name</b></label>";
    echo "              <input type ='text' id='lastname' name='lastname' value='$l_name' required maxlength='12'/>";
    echo "          </div>";
    echo "      </div>";

/*********************************DISPLAY BIO***********************/

    echo "      <div class='columns panel text-left large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "          <h3 style='color: #008cbb'> Bio: </h3><br>";
    echo "          <div class='large-12 medium-12 small-12 columns>";
    echo "              <label for='bio'><b> </b></label>";
    echo "              <textarea maxlength='1000' style='height: 200px' name='bio' id='bio'> $bio </textarea>";
    echo "          </div>";
    echo "      </div>";

/*********************************DISPLAY EMAIL***********************/
    echo "      <div class='columns panel text-left large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "          <h3 style='color: #008cbb'> Email: </h3><br>";
    echo "          <div class='large-12 medium-12 small-10 columns>";
    echo "              <label for='email'><b> </b></label>";
    echo "              <input type ='text' id='email' name='email' value='$email' required maxlength='100'/>";
    echo "          </div>";
    echo "      </div>";
    
/*********************************SUBMIT BUTTON***********************/

    echo "      <div class='columns panel text-center large-8 large-centered medium-8 medium-centered  small-10 small-centered '>";
    echo "          <div class='large-12 medium-12 small-10 columns/>";
    echo "              <label for='submit'><b> </b></label>";
    echo "              <br><input type='submit' id='submit' name='submit' class='button' value='Save Changes'/>";
    echo "          </div>";
    echo "      </div>";
    echo "  </div>";
    echo "</form>";

?>
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
/**********************************************************************************************************/
/**********ELSE USER NOT LOGGED IN, SHOW MESSAGE INSTEAD******************************/
else{
?>
<div class='row'>
    <div class='large-7 columns panel large-centered text-center'>
        <h5>You must be logged in in order to view your profile!</h5>
        <h6>You can log in <a href='login.php'>here! </a></h6>
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
<?php
    exit;
}
/**************************************************************************************/
?>

