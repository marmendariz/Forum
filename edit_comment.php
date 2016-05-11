<?php
include_once 'lib.php';

if(isset($_POST['user_id'])){
    if(!($db = db_connect())){
        echo "Database error";
        exit;
    }
    
    $commentText = mysqli_real_escape_string($db, input_clean($_POST['text']));
    
    $user_id = input_clean($_POST['user_id']);
    $com_id = input_clean($_POST['com_id']);


    $comUpdate = "update com set com_text=? where com_id=?";
    $stmt = $db->prepare($comUpdate);
    $stmt->bind_param('si',$commentText, $com_id);
    $stmt->execute();

    
    $userEditCom = "update user_edit_com set edit_date=now() where user_id=?";
    $stmt = $db->prepare($userEditCom);
    $stmt->bind_param('i',$user_id);
    $stmt->execute();
    
    
    $return = array();
    $return['text'] = $commentText;
    echo json_encode($return);
}
?>
