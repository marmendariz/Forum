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
    <title>Quadcore Forum | Create New Category</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src='js/jquery-1.12.0.min.js' type='text/javascript'></script>
    <script src="js/vendor/modernizr.js"></script>
  </head>

<body>

<?php

include_once 'header.php';

$login_failed = false;

$category_flag = true;
$categorytext_flag = true;

//********** Pass the current cat_level in *********/
if(null == ($cat_level = filter_input(INPUT_GET, cat_level, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) )){
    echo 'Error. Invalid category level<br>';
    exit;
}


//********** Pass the Parent Cat ID in ********* /
if(null == ($parent_cat_id = filter_input(INPUT_GET, parent_cat_id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) )){
    echo 'Error. Invalid category ID<br>';
    exit;
}
$parent_cat_id_backup = $parent_cat_id;
$parent_cat_id_backup2 = $parent_cat_id;


/********** Connect to the Database ************/
if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}

/****** Query info about parent cat level ******/
    $current_cat_level = input_clean($_GET['cat_level']);
    $pcat_level = $current_cat_level - 1;
    //$pcat_level = $cat_level - 1;
    $query = 'select * from category where cat_level=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('i',$pcat_level);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($cat_id, $cat_name, $cat_level, $cat_text, $parent_cat_id);    
    $stmt->fetch();
/***********************************************/




/******************* If user is logged In ************************/

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
    $stmt->close();

/**** Query information about parent category **********/

    $passed_in_parent_id = $parent_cat_id_backup;
    $query = 'SELECT * FROM category WHERE cat_id=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $passed_in_parent_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($parent_actual_id, $parent_actual_category_name, $parent_actual_cat_level, $parent_actual_cat_text, $parent_actual_parent_cat_id);
    $stmt->fetch();
    $stmt->close(); 
    
    

    /********** Check if a form was submitted ********/
    if(isset($_POST['submit'])){

/***********  Category Name input ***************/

        if (!isset($_POST['categoryname']) || empty($_POST['categoryname'])) {
            $category_flag = false;
        } else {
            $categoryname = input_clean($_POST['categoryname']);
        }

/********  Category Text Input ************/

        if (!isset($_POST['categorytext']) || empty($_POST['categorytext'])) {
            $categorytext_flag = false;
        } else {
            $categorytext = input_clean($_POST['categorytext']);
        }

        if($category_flag && $categorytext_flag){

            if(!($db = db_connect())){
                echo "database error<br>";
                exit;
            }
            
            
            $cat_level = input_clean($_POST['cat_level']);
            //$parent_cat_id = input_clean($_POST['parent_cat_id']);
            
            /************** Insert in Database -- Category **************/
            
            $insert_pid = input_clean($_POST['parent_cat_id']);
//            $insert_pid = mysqli_real_escape_string(trim($insert_pid));
         
            $query = 'Insert into category (cat_name,cat_level,cat_text,parent_cat_id) values (?,?,?,?)';
            $stmt = $db->prepare($query);

            $categoryname = mysqli_real_escape_string($db, $categoryname);
            $categorytext = mysqli_real_escape_string($db, $categorytext);


            $stmt->bind_param('sisi', $categoryname,$cat_level,$categorytext,$insert_pid);
            if (!$stmt->execute()) {
                echo '<br><br><br>Error!!<br>';
                $stmt->close();
                $db->close();
                exit;
            }
            $stmt->close();
            
            /************** Insert in Database -- Category **************/
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
        /**********************************************************/
    } 
    /***************************************************************************/

        
    /*********************************display form***********************/

?>
    <div class ='row'>
        <div class='columns panel text-center large-8 large-centered medium-8 medium-centered  small-10 small-centered '>
        <h2 style='color: #008cbb'>Create New Category in <?echo $parent_actual_category_name?></h2>
        </div>
    </div>
<?php
   $pcat_level++; 
    echo " <form method= 'post' action= 'create_new.php?cat_level=$pcat_level&parent_cat_id=$parent_cat_id_backup'>";
    //echo " <form method= 'post' action= 'create_new.php'>";

    //echo "<input type='hidden' id='parent_cat_id_post' name='parent_cat_id_post' value ='$parent_cat_id_backup' />";  
    echo "<input type='hidden' id='cat_level' name='cat_level' value ='$pcat_level' />";  
    echo "<input type='hidden' id='parent_cat_id' name='parent_cat_id' value ='$parent_cat_id' />";  
?>
<!------------------------------display new category name text box -------------------------->
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
                    <textarea id='categorytext' name='categorytext' maxlength = '500' rows='8' cols='9'></textarea>                 
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
    $query = 'select * from category where cat_level=? AND parent_cat_id=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('ii',$passed_cat_level, $parent_cat_id_backup);
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

