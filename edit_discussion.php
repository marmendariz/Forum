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
    <title>Quadcore Forum | Edit Discussion</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>

<?php
include_once 'header.php';
$login_failed = false;


if(null == ($dis_id_passed = filter_input(INPUT_GET, dis_id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) )){
      echo 'Error. Invalid discussion ID<br>';
      exit;
  }

/************ Make sure that the Discussion Exists ************/
    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }

$query = 'select * from discussion where dis_id=?';
$stmt = $db->prepare($query);
$stmt->bind_param('i', $dis_id_passed);
$stmt->execute();
$stmt->store_result();
$rows = $stmt->num_rows();
$stmt->bind_result($dis_id_verified, $dis_name_verified, $dis_text_verified, $dis_flag, $upvote_count, $downvote_count);
if ($rows) {
    $stmt->fetch();
    $discussion_verified = true;
} else {
    echo "<br><br><h4>Error! Discussion Does Not Exist!</h4>";
    exit;
}

$discussion_id_backup = $discussion_id_passed;


/*************************************IF USER IS LOGGED IN*************************************************/
if(isset($_SESSION['valid_user'])){

    /*****************If a form was submitted, we are going to check before updating********/
    if(isset($_POST['submit'])){
        $nameStat = true;
        $textStat = true;

        
        /*************************** Discussion NAME ******************************/
        if(!isset($_POST['disname']) || empty($_POST['disname']))
            $nameStat = false;
        else{
            $disname = input_clean($_POST['disname']);
            //if(!preg_match('/^[a-zA-Z-]+$/',$disname))
               // $nameStat = false;
        }
        /******************************************************************/

        /************************* Discussion Text *****************************/
        if(!isset($_POST['distext']) || empty($_POST['distext'])){
            $textStat = false;
        }
        else{
            $distext = input_clean($_POST['distext']);
            //if(!preg_match('/^[a-zA-Z-]+$/',$distext))
               // $textStat = false;
        }

       /******************************************************************/

        if($nameStat && $textStat){
                      if(!($db = db_connect())){
                echo "Database error<br>";
                exit;
            }

            mysqli_real_escape_string($db, $disname);
            mysqli_real_escape_string($db, $distext);

            $query = 'UPDATE discussion SET dis_name=?, dis_text=? where dis_id=?';
            $stmt = $db->prepare($query);
            $stmt->bind_param('ssi', $disname, $distext, $dis_id_verified);
            $stmt->execute();
            /*Redirects */
            header("Location: discussion.php?dis_id=$dis_id_verified");
        }

    }
    
    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }

    if(isset($_POST['submit2'])){
        $query = 'CALL delete_discussion (?)';
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $dis_id_verified);
        $stmt->execute();
        header("Location: show_parent_cat.php");
    }

    /*****************DISPLAY FORM***********************/

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
            <h2 style='color: #008cbb'>Editing <?echo $dis_name_verified ?> </h2>
        </div>
    </div>

    <form method='post' action='edit_discussion.php?dis_id= <?echo $dis_id_verified?>' enctype='multipart/form-data'>


<!------------------------------display edit discussion name text box -------------------------->
    <div class ='row'>
    <div class='columns panel text-left large-8 medium-8 small-10 small-centered '>
        <h3 style='color: #008cbb'> Edit Discussion Name: </h3><br>
            <div class='row'>
                <div class='large-6 medium-6 large-10 columns large-centered'>
                    <label for='disname'><b>Discussion Name</b></label>
                    <input type='text' id='disname' name='disname' required maxlength = '50' value= '<? echo $dis_name_verified ?>' /> 
                </div>
            </div>
         <h3 style='color: #008cbb'> Edit Discussion Description: </h3><br>
            <div class='row'>
                <div class='large-6 medium-6 large-10 columns large-centered'>
                    <label for='distext'><b>Discussion Description</b></label>
                    <textarea id='distext' name='distext' maxlength = '500' rows='8' cols='9'> <? echo $dis_text_verified ?> </textarea>                 
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
                  <br><input type='submit' id='submit' name='submit' class='button' value='Save Edits'/>
            </div>
            </div>
      </div>
 
<!-------------------------------DELETE BUTTON---------------------------------->
            <hr>
    
        <div class='row'>
         <div class='columns panel text-center large-8 medium-8 small-10 small-centered '>
             <div class='row'>
             <div class='large-12 medium-12 small-10 columns'>
                  <label for='submit2'><b> </b></label>
                  <br><input type='submit' id='submit2' name='submit2' class='medium alert button' value='DELETE'/>
            </div>
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
        <h5>You must be logged in!</h5>
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

