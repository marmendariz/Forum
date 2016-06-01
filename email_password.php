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
$email_failed = false;

/****************QUERY DB FOR LOGIN************************/
if(isset($_POST['username']) && isset($_POST['email'])){
    if(!($db = db_connect()))
    {
        echo "Database error<br>";
        exit;
    }
    else{
        $username = mysqli_real_escape_string($db, input_clean($_POST['username']));
        $email = mysqli_real_escape_string($db,input_clean($_POST['email']));
        $query = 'select user_name from user where email=?';
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($username2);
        $stmt->fetch();
        
        if($username2 == $username){
            $hash= hash('sha512',rand(0,1000));
            $query2 = 'select user_id from user where user_name = ?';
            $stmt2 = $db->prepare($query2);
            $stmt2->bind_param('s', $username2);
            if(!$stmt2->execute()){
                echo '<br><br> Error in userid';
            }
            $stmt2->store_result();
            $stmt2->bind_result($user_id);
            $stmt2->fetch();

            $stmt2->close();
            $date = date('Y-m-d H:i:s'); 
            $query3 = "Insert into reset_password values (?,'".date('Y-m-d H:i:s')."',?)";
            $stmt3= $db->prepare($query3);
            $stmt3->bind_param('is',$user_id,$hash);
            if(!$stmt3->execute()){
                echo'<br><br> Error';
                echo $user_id;
                echo $date;
                echo $hash;
            }



            //echo "usernames matched";
            $email_failed=false;

            $to      = $email; // Send email to our user
            $subject = 'Password Reset'; // Give the email a subject 
            $message = '

                Hello,
                We have recieved a request for your Quadcore password to be reset. If you did not send this request please ignore this email. 
                The request was made for the account below:
                ------------------------
                Username: '.$username.'
                ------------------------

                Please click this link to reset your password:
                http://www.cs.csubak.edu/~quadcore/Forum/password_recover.php?hash='.$hash.'

                '; // Our message above including the link

            $headers = 'From:noreply@quadcore.cs.csubak.edu' . "\r\n"; // Set from headers
            mail($to, $subject, $message, $headers); // Send our email

            header("Location:https://www.cs.csubak.edu/~quadcore/Forum/right.php");

        }
        else{
            $email_failed=true;
            header("Location:https://www.cs.csubak.edu/~quadcore/Forum/wrong.php");
        }


        /******* COOKIE STUFF *********/
        if(isset($_POST['rememberMe'])){
            $rememberMe = input_clean($_POST['rememberMe']);
            if(input_clean($_POST['rememberMe'])=='yes'){
                $exp = time()+(86400*30);
                $token = gen_token();
                /**/
                setcookie("selector", $selector, $exp);
                setcookie("token", $token, $exp);
                setcookie("active", true, $exp);
                /**/
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
        $stmt->close();
        $stmt2->close();
        $stmt3->close();
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
  <form method='post' action='email_password.php'>

    <div class="row">
      <div class='large-3 columns large-centered text-center'>
      <h5>Password Recovery</h5>
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
        <label for='email'><b>Email:</b></label>
        <input type="text" id = 'email' placeholder="" name='email'/>
      </div>         
    </div>


    <div class='row'>
      <div class='columns large-6 large-centered medium-6 medium-centered small-12 small-centered'>

     <input type='submit' class='button expand' value='Submit'>    

   </div>
    </div>

<?php
/*if($email_failed){
    echo "
        <div class='row'>
        <div class='columns large-4 large-centered text-center'>
        <h5 id='login_error'>Email Failed</h5>
        </div>
        </div>
        ";

}*/
?>


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
