<?php
include_once 'lib.php';

if(isset($_POST['username'])){
    if(!($db = db_connect())){
        echo "Database error";
        exit;
    }

    $user_id = input_clean($_POST['user_id']);
    $dis_id = input_clean($_POST['dis_id']);

        $bookInsert = "Delete from bookmarked 
                       where user_id=? and dis_id=?";
    $stmt = $db->prepare($bookInsert);
    $stmt->bind_param('ii', $user_id, $dis_id);
    $stmt->execute();
    /************************************************************/

}
?>
