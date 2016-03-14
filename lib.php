<?

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

/*********************Sets up session path************************/
function set_path(){
    ini_set('session.save_path','/tmp');
    ini_set('session.gc_probability',1);
    ini_set('session.cookie_httponly',1);
}
/**************************************************************/

/********************Redirects to home page********************/
function redirect_home(){
    header("Location: index.php");
}
/****************************************************************/

/********************Redirects to login page********************/
function redirect_login(){
    header("Location: login.php");
}
/****************************************************************/

/****************FORCE SSL SECURED CONNECTION********************/
function force_ssl(){
if(empty($_SERVER["HTTPS"]) ||  $_SERVER["HTTPS"] != "on"){
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
}
/****************************************************************/
?>
