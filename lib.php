<?php

/*************Cleans up string input****************/
function input_clean($input){
    return htmlspecialchars(strip_tags(trim($input)));
}
/*****************************************************/

/**************Returns connection or false**************************/
function db_connect()
{
    @ $link =  new mysqli('localhost','quadcore','Vek,6zum','quadcore');
    if(mysqli_connect_errno()){
        return false;
    }
    else
        return $link;
}
/*****************************************************************/

?>
