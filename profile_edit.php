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

/*************************************IF USER IS LOGGED IN*************************************************/
if(isset($_SESSION['valid_user'])){

/*****************If a form was submitted, we are going to check before updating********/
    if(isset($_POST['submit'])){
        $fnStat = true;
        $mnStat = true;
        $lnStat = true;
        $bioStat = true;
        $emStat = true;

        /*************************** FIRST NAME ******************************/
        if(!isset($_POST['firstname']) || empty($_POST['firstname']))
            $fnStat = false;
        else{
            $fname = input_clean($_POST['firstname']);
            if(!preg_match('/^[a-zA-Z-]+$/',$fname))
                $fnStat = false;
        }
        /******************************************************************/

        /************************* MIDDLE NAME *****************************/
        if(!isset($_POST['middlename']) || empty($_POST['middlename'])){
            $mnStat = false;
        }
        else{
            $mname = input_clean($_POST['middlename']);
            if(!preg_match('/^[a-zA-Z-]+$/',$mname))
                $mnStat = false;
        }
        /******************************************************************/
       
        /*************************** LAST NAME ******************************/
        if(!isset($_POST['lastname']) || empty($_POST['lastname'])){
            $lnStat = false;
        }
        else{
            $lname = input_clean($_POST['lastname']);
            if(!preg_match('/^[a-zA-Z-]+$/',$lname))
                $lnStat = false;
        }
        /******************************************************************/

        /*************************** BIO ******************************/
        if(!isset($_POST['bio']) || empty($_POST['bio'])){
            $bioStat = false;
        }
        else{
            $bio = input_clean($_POST['bio']);
        }
        /******************************************************************/
        
        /*************************** EMAIL ******************************/
        if(!isset($_POST['email']) || empty($_POST['email'])){
            $emStat = false;
        }
        else{
            $email = input_clean($_POST['email']);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if(filter_var($email, FILTER_VALIDATE_EMAIL)===false)
                $emStat = false;
        }
        /******************************************************************/

        if($fnStat && $mnStat && $lnStat && $bioStat && $emStat){
            /*Do update stuff here*/

            /*Kevin, make sure you call
                mysqli_real_escape_string($db, var)
             for each variable after you make connection to db*/
            
            /*Redirects to profile page*/
            header("Location: profile.php");
        }

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
?>
    <div class ='row'>
        <div class='columns panel text-center large-8 large-centered medium-8 medium-centered  small-10 small-centered '>
            <h2 style='color: #008cbb'>Editing <?echo $username?>'s Profile</h2>
        </div>
    </div>

    <form method='post' action='profile_edit.php'>
<!------------------------------DISPLAY NAME-------------------------->
    <div class ='row'>
    <div class='columns panel text-left large-8 medium-8 small-10 small-centered '>
        <h3 style='color: #008cbb'> Name: </h3><br>
            <div class='row'>
            <div class='large-6 medium-6 small-10 columns small-centered'>
                  <label for='firstname'><b>First Name</b></label>
                  <input type ='text' id='firstname' name='firstname' value='<?echo $f_name?>' required maxlength='12'/>
                  <label for='middlename'><b>Middle Name</b></label>
                  <input type ='text' id='middlename' name='middlename' value='<?echo $m_name?>' required maxlength='12'/>
                  <label for='lastname'><b>Last Name</b></label>
                  <input type ='text' id='lastname' name='lastname' value='<?echo $l_name?>' required maxlength='12'/>
            </div>
            </div>
    </div>
    </div>
<!------------------------------DISPLAY BIO------------------------------>
    <div class='row'>
    <div class='columns panel text-left large-8 medium-8 small-10 small-centered '>
        <h3 style='color: #008cbb'> Bio: </h3><br>
        <div class='row'>
        <div class='large-12 medium-12 small-12 columns'>
            <label for='bio'><b> </b></label>
            <textarea maxlength='1000' style='height: 200px' name='bio' id='bio'><? echo $bio ?></textarea>
        </div>
        </div>
    </div>
    </div>
<!-------------------------------DISPLAY EMAIL---------------------------------->
    <div class='row'>
    <div class='columns panel text-left large-8 medium-8 small-10 small-centered '>
        <h3 style='color: #008cbb'> Email: </h3><br>
        <div class='row'>
        <div class='large-12 medium-12 small-10 columns'>
            <label for='email'><b> </b></label>
            <input type ='text' id='email' name='email' value='<? echo $email ?>' required maxlength='100'/>
        </div>
        </div>
    </div>
    </div>
<!-------------------------------SUBMIT BUTTON---------------------------------->
        <div class='row'>
         <div class='columns panel text-center large-8 medium-8 small-10 small-centered '>
             <div class='row'>
             <div class='large-12 medium-12 small-10 columns'>
                  <label for='submit'><b> </b></label>
                  <br><input type='submit' id='submit' name='submit' class='button' value='Save Changes'/>
            </div>
            </div>
      </div>
      </div>
    </form>

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
