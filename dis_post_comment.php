<?php
include_once 'lib.php';

if(isset($_POST['username'])){
    if(!($db = db_connect())){
        die("Database error");
        exit;
    }

    $commentText = mysqli_real_escape_string($db, input_clean($_POST['commentText']));
    $user_id = input_clean($_POST['user_id']);
    $dis_id = input_clean($_POST['dis_id']);

    $comInsert = "Insert into com (com_level, 
                  com_text ,parent_com_id) values (1,?,1)";
    
    $stmt = $db->prepare($comInsert);
    $stmt->bind_param('s',$commentText);
    $stmt->execute();

    $com_id = mysqli_insert_id($db);
    $comInsert = "Insert into user_edit_com (user_id, com_id, edit_date, edit_type) 
        values (?,?,'".date('Y-m-d H:i:s')."',0)";
    $stmt = $db->prepare($comInsert);
    $stmt->bind_param('ii',$user_id,$com_id);
    $stmt->execute();
 
    $comInsert = "Insert into dis_cont_com values (?,?)";
    $stmt = $db->prepare($comInsert);
    $stmt->bind_param('ii',$dis_id,$com_id);
    $stmt->execute();

    $image = "select profile_image from user where user_id=$user_id";
    $stmt = $db->prepare($image);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($url);
    $stmt->fetch();
    $stmt->close();
   
    /******** Return Comment Text and Comment id  **********/
    $result = array();
    $result['text'] = stripslashes($commentText);
    $result['com_id'] = $com_id;
    $result['image_url'] = stripslashes($url);
    echo json_encode($result);
    
    /******************************************************/

}
?>
