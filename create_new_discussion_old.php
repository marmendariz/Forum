<?include_once 'lib.php';
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
    <title>Quadcore Forum | Create New Discussion</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src='js/jquery-1.12.0.min.js' type='text/javascript'></script>
    <script src="js/vendor/modernizr.js"></script>
  </head>

<body>

<?php

include_once 'header.php';

$login_failed = false;

$dis_flag = true;
$distext_flag = true;

//********** Pass the Parent Cat ID in ********* /

if(null == ($parent_cat_id = filter_input(INPUT_GET, cat_id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) )){
    echo 'Error. Invalid category ID<br>';
    exit;
}

$parent_cat_backup = $parent_cat_id;

/********** Connect to the Database ************/

if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}

/********** If user is logged In ***************/

if(isset($_SESSION['valid_user'])){

    
    /********** Query information about the user ***********/

    $username = input_clean($_SESSION['valid_user']);
    $query = 'SELECT user_id, user_type, ban_flag, f_name, m_name, 
        l_name, bio, email, date_joined, com_count, 
        dis_count, upvote_count, downvote_count 
        FROM user WHERE user_name=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $user_type, $ban_flag, 
        $f_name, $m_name, $l_name, 
        $bio, $email, $date_joined, 
        $com_count, $dis_count, 
        $up_count, $down_count);
    
    $stmt->fetch();

    
    /********** Check if a form was submitted ********/

    if(isset($_POST['submit'])){

/***********  Discussion Name input ***************/

        if (!isset($_POST['disname']) || empty($_POST['disname'])) {
            $dis_flag = false;
        } else {
            $disname = input_clean($_POST['disname']);
            if(!preg_match('/^[a-zA-Z0-9]+$/',$disname))
                $dis_flag = false;
        }

/********  Discussion Text Input ************/

        if (!isset($_POST['distext']) || empty($_POST['distext'])) {
            $distext_flag = false;
        } else {
            $distext = input_clean($_POST['distext']);
            if(!preg_match('/^[a-zA-Z0-9]+$/',$distext))
                $distext_flag = false;
        }

        if($dis_flag && $distext_flag){

            if(!($db = db_connect())){
                echo "database error<br>";
                exit;
            }

            $query = 'Insert into discussion (dis_name,dis_text,dis_flag,upvote_count,downvote_count) values (?,?,?,?,?)';
            $stmt = $db->prepare($query);

            $disname = mysqli_real_escape_string($db, $disname);
            $distext = mysqli_real_escape_string($db, $distext);

            $stmt->bind_param('ssiii', $disname,$distext,$disflag,$upvotecount,$downvotecount);
            if (!$stmt->execute()) {
                echo '<br><br><br>Error!!<br>';
                $stmt->close();
                $db->close();
                exit;
            }
            $stmt->close();



          /* 
            $cat_id2 = mysqli_insert_id($db);
        $admin_cat_insert = "Insert into ad_edit_cat (user_id, cat_id, edit_date, edit_type) values (?,?,'".date('Y-m-d H:i:s')."', 0)";
            $stmt-> $db->prepare($query);
            $stmt-> bind_param('ii',$user_id, $cat_id2);
            $stmt-> execute();
            $stmt-> close();
*/
            
            //$db->close();
        }
    }

    /****** Query info about parent based on cat level ******/

    $pcat_level = input_clean($_GET['cat_level']);
    $pcat_level--;
    $query = 'select * from category where cat_level=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('i',$pcat_level);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($cat_id, $cat_name, $cat_level, $cat_text, $parent_cat_id);    
    $stmt->fetch();
    
    /*********************************display form***********************/

?>
    <div class ='row'>
        <div class='columns panel text-center large-8 large-centered medium-8 medium-centered  small-10 small-centered '>
        <h2 style='color: #008cbb'>Create New Discussion in <?echo $cat_name?></h2>
        </div>
    </div>
<?php
   $pcat_level++; 
   echo " <form method = 'post' action = 'create_new.php?cat_level=$pcat_level&parent_cat_id=$parent_cat_id'>";
?>
<!------------------------------display new category text box -------------------------->
    <div class ='row'>
    <div class='columns panel text-left large-8 medium-8 small-10 small-centered '>
        <h3 style='color: #008cbb'> New Category Name: </h3><br>
            <div class='row'>
                <div class='large-6 medium-6 large-10 columns large-centered'>
                    <label for='categoryname'><b>Category Name</b></label>
                    <input type='text' id='categoryname' name='categoryname' required maxlength = '50' /> 
                </div>
            </div>
         <h3 style='color: #008cbb'> New Category Description: </h3><br>
            <div class='row'>
                <div class='large-6 medium-6 large-10 columns large-centered'>
                    <label for='categorytext'><b>Category Description</b></label>
                    <input type='text' id='categorytext' name='categorytext' required maxlength = '50' /> 
                </div>
            </div>

    </div>
    </div>

<!-------------------------------DISPLAY existing categories---------------------------------->
    <div class='row'>
    <div class='columns panel text-left large-8 medium-8 small-10 small-centered '>
        <h3 style='color: #008cbb'> Existing Categories at Current Level: </h3><br>
        <div class='row'>
        <div class='large-12 medium-12 small-10 columns'>

<?php

    /********** Get information about the existing categories ***********/

    $passed_cat_level = input_clean($_GET['cat_level']);
    $query = 'select * from category where cat_level=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('i',$passed_cat_level);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($cat_id2, $cat_name2, $cat_level2, $cat_text2, $parent_cat_id2);    

    while($stmt->fetch()){
        echo "<h3>$cat_name2</h3>";
    }

?> 

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
                  <br><input type='submit' id='submit' name='submit' class='button' value='Create Category'/>
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
        <h5>You must be logged in in order to create a new category!</h5>
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


