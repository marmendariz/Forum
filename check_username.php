<?php

if(isset($_POST['username'])){
    $username = htmlspecialchars(trim($_POST['username']));
    @ $db = new mysqli('localhost','quadcore','Vek,6zum','quadcore');
    if(mysqli_connect_errno()){
        echo "Database error<br>";
        exit;
    } 

    else{
        $query = 'select * from login where username=?';
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
    }
    $db->close();
}
if(empty($_POST['username']))
    echo 'Choose a username';
?>
