<?php
include_once 'lib.php';

define('LENGTH', 8);

if(isset($_POST['password'])){
    if(empty($_POST['password']) || $_POST['password']==''){
        echo 'Choose a password';
        exit;
    }
    $pwd = input_clean($_POST['password']);
    check_password($pwd);
}

/******************/
function check_password($pass){
    $length = constant('LENGTH');
    $size = strlen($pass);
    $error = '';

    if($size<$length){
        $error = 'Too short';
    }
    if($size>=$length){
        $error = 'Great!';
    }
    if(!preg_match('/[0-9]+/',$pass)){
        $error = "Invalid password";
    }
    
    if(!preg_match('/[\W]+/',$pass)){
        $error = "Invalid password";
    }
    
    if(!preg_match('/[A-Z]+/',$pass)){
        $error = "Invalid password";
    }
    
    if(!preg_match('/[a-z]+/',$pass)){
        $error = "Invalid password";
    }
    echo $error;
}
/******************/
?>
