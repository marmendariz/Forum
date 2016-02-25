<?php
include_once 'lib.php';

if(isset($_POST['username'])){
    if(!($db = db_connect())){
        echo "Database error";
        exit;
    } 
        $username = mysqli_real_escape_string($db,input_clean($_POST['username']));
        $query = 'select * from user where user_name=?';
        $stmt = $db->prepare($query);
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        if($num_rows>0)
            echo 'Username taken';
        if($num_rows==0 && !empty($username))
            echo 'Username available!';
        $stmt->close();
    $db->close();
}
if(empty($_POST['username']))
    echo 'Choose a username';
?>
