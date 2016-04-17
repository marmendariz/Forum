<?
include_once 'lib.php';
set_path();
force_ssl();
session_start();

$login_failed = false;

    if(!($db = db_connect()))
    {
        echo "Database error<br>";
        exit;
    }
        $hashed=crypt($pwd,'$6$'.$salt);

        $query = 'select user_id from user';
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss',$username, $hashed);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        if($num_rows>0){
            $stmt->bind_result($user_id);
            while($stmt->fetch()){
                $selector = gen_token(6);
                echo $selector."<br>";
                $q = "update user 
                    set selector='$selector'
                    where user_id = $user_id";
                $stmt2 = $db->prepare($q);
                //$stmt2->execute();
            }
        }
            $stmt->close();
        $db->close();
?>
