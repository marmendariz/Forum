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


        if(!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error']==UPLOAD_ERR_NO_FILE){
            echo "<br><br><br>Error<br>";
        }
        else{
            $dir="/home/stu/quadcore/public_html/uploads/";
            $uploadOk=1;
            $imageFileType=pathinfo($file,PATHINFO_EXTENSION);

            $check = getimagesize($_FILES["fileToUpload"]['tmp_name']);

            if($check!==false){
                $filename = strtolower($_FILES["fileToUpload"]['name']);
                $whitelist = array('jpg','jpeg');


                if(!in_array(end(explode('.', $filename)), $whitelist))
                {
                    echo '<script language="javascript">';
                    echo 'alert("Invalid File Type")';
                    echo '</script>';
                    exit(0);
                }


                $uploadOk = 1;

            }else{
                $uploadOk=0;
            }
            if($uploadOk==0){
                echo "<script type='text/javascript'>alert('$sorry');</script>"; 
            }
            else{
                $temp=explode(".",$filename);
                $fp=fopen('/dev/urandom','r');
                $truename=base64_encode(fread($fp,10));

                $newfilename=$truename.'.'.end($temp);
                $resizeim=$truename.'.jpg';
                
                fclose($fp);
                if(!($db = db_connect())){
                    echo "Database error<br>";
                    exit;
                }

                if(move_uploaded_file($_FILES["fileToUpload"]['tmp_name'],$dir.$newfilename)){
                    $name=$_FILES["fileToUpload"]["name"];
                    $src="/home/stu/quadcore/public_html/uploads/".$newfilename;
                    chmod($src,0744);
                    
                    $location="https://cs.csubak.edu/~quadcore/uploads/".$resizeim;
                 
                    $resize="/home/stu/quadcore/public_html/uploads/".$resizeim;
                    $img_quality=100;

                    $im=imagecreatefromstring(file_get_contents($src));
                    $im_w=imagesx($im);
                    $im_h=imagesy($im);
                    $color=imagecreatetruecolor($im_w,$im_h);

                    imagecopyresampled($color,$im,0,0,0,0,$im_w,$im_h,$im_w,$im_h);
                    $whiteBackground=imagecolorallocate($color,255,255,255);
                    imagefill($color,0,0,$whiteBackground);
                    imagejpeg($color,$resize,$img_quality);

                    chmod($resize,0744);

                    $query="Update user set profile_image = ? where user_name = ?";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param('ss',$location,$_SESSION['valid_user']); 
                    if(!$stmt->execute()){
                        echo 'Failure to save to database';
                        $stmt->close();
                        exit();
                    }
                    $stmt->close();
                }
            }
        }

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

            if(!($db = db_connect())){
                echo "Database error<br>";
                exit;
            }


            mysqli_real_escape_string($db, $fname);
            mysqli_real_escape_string($db, $mname);
            mysqli_real_escape_string($db, $lname);
            mysqli_real_escape_string($db, $bio);
            mysqli_real_escape_string($db, $email);

            $query = 'UPDATE user SET f_name=?, m_name=?, l_name=?, bio=?, email=? where user_name=?';
            $stmt = $db->prepare($query);
            $stmt->bind_param('ssssss', $fname, $mname, $lname, $bio, $email, $_SESSION['valid_user']);
            $stmt->execute();
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

    <form method='post' action='profile_edit.php' enctype='multipart/form-data'>
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

<!---------------------------PROFILE PICTURE------------------------------>
    <div class='row'>
    <div class='columns panel text-left large-8 medium-8 small-10 small-centered '>
        <h3 style='color: #008cbb'> Profile Picture: </h3><br>
        <div class='row'>
        <div class='large-12 medium-12 small-12 columns text-center'>
<?php
    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }

    $user2=$_SESSION['valid_user'];
    $query1 ="Select profile_image from user where user_name = ?";
    $stmt = $db->prepare($query1);
    $stmt->bind_param('s',$user2);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($pimage);
    $stmt->fetch();
    echo "<img src='$pimage'>";

?>
        </div>
        </div>
        <div class='row'>
        <div class='large-12 medium-12 small-12 columns text-center'>
          <input type="file" name="fileToUpload" id="fileToUpload">
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
