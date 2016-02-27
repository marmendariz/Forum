<?php
include_once 'lib.php';
$length = 5;

if(isset($_POST['password'])){
    $pwd = input_clean($_POST['password']);
    $size = strlen($pwd);
    if($size<$length && $size>0){
        echo 'Too short';
    }
    if($size>=5)
        echo 'Great!';
}
if(empty($_POST['password']) || $_POST['password']=='')
    echo 'Choose a password';
?>
