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
    ini_set('session.save_path','tmp');
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

/************ GENERATE RANDOM STRING FOR COOKIE TOKEN ***********/
function gen_token($length = 20){
    return bin2hex(openssl_random_pseudo_bytes($length));
}
/****************************************************************/

/************** CHECK COOKIES FOR AUTOMATIC LOGIN ***************/
function auto_login(){
    if(!isset($_SESSION['valid_user']) && isset($_COOKIE['active'])
        && $_COOKIE['active']==1){
            $token = input_clean($_COOKIE['token']);
            $selector = input_clean($_COOKIE['selector']);
            if(!($db = db_connect())){
                echo "<br><br><br>Database Error";
                exit;
            }
            else{
                $selector = mysqli_real_escape_string($db, $selector);
                $hToken = crypt($token, "$5$");
                $query = "select user_id, user_name,token from user
                    where selector=?";
                $stmt=$db->prepare($query);
                $stmt->bind_param('s',$selector);
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows>0){
                    $stmt->bind_result($user_id, $user_name, $token);
                    $stmt->fetch();
                    if(hash_equals($hToken, $token)){
                        $_SESSION['valid_user'] = $user_name;
                        $_SESSION['user_id'] = $user_name;
                    }
                    else{
                        setcookie('active', null, time()-3600);
                        setcookie('token', null, time()-3600);
                        setcookie('selector', null, time()-3600);
                    }
                }
            }
    }
}
/****************************************************************/

if(!function_exists('hash_equals')){
    function hash_equals($str1, $str2){
        if(strlen($str1) != strlen($str2))
            return false;
        else{
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i=strlen($res)-1; $i>=0; $i--)
                $ret |= ord($res[$i]);
            return !$ret;
        }
    }
}

?>
