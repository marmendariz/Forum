<?php
include_once 'lib.php';

if(isset($_POST['username'])){
    if(!($db = db_connect())){
        echo "Database error";
        exit;
    }

    $user_id = input_clean($_POST['user_id']);
    $dis_id = input_clean($_POST['dis_id']);

        $bookInsert = "Insert into bookmarked (user_id, 
                    dis_id, date) values (?,?,'".date('Y-m-d H:i:s')."')";
    $stmt = $db->prepare($bookInsert);
    $stmt->bind_param('ii', $user_id, $dis_id);
    $stmt->execute();
    /************************************************************/

}
?>
