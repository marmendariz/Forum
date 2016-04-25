<?
/*
    comment_vote.php 
 */

include_once 'lib.php';

$user_id = intval(input_clean($_POST['user_id']));
$com_id = intval(input_clean($_POST['com_id']));
$vote = intval(input_clean($_POST['vote']));

$return = array();

//check if row exists in user_vote_com
//if not, add to either upvote_count or downvote_count in com

$vote_query = "select vote from user_vote_com 
                where user_id=?
                and com_id=?";

if(!($db = db_connect())){
    echo "<br><br><br>Database Error";
    exit;
}

$user_id = mysqli_real_escape_string($db, $user_id);
$com_id = mysqli_real_escape_string($db, $com_id);

$stmt = $db->prepare($vote_query);
$stmt->bind_param('ii',$user_id, $com_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($old_vote);

if($stmt->num_rows>0){ //If they have previously voted on this comment
    $stmt->fetch();
    $stmt->close();

    
    if($old_vote==$vote){
        
        //Delete row from user_vote_com
        $delete = "delete from user_vote_com where user_id = ? and com_id = ?";
        $stmt = $db->prepare($delete);
        $stmt->bind_param('ii',$user_id, $com_id);
        $stmt->execute();
        $stmt->close();

        if($vote==1){
            //subtract from upvote_count
            $update ="update com
                    set upvote_count = upvote_count-1
                    where com_id=$com_id";
        $return['cur_vote'] = -1;
        }
        else{
            //subtract from downvote_count
        $update =  "update com
                    set downvote_count = downvote_count-1
                   where com_id=$com_id";
        $return['cur_vote'] = 1;
        }
        $stmt = $db->prepare($update);
        $stmt->execute();
        $stmt->close();
    }
    else{
        //Insert into user_vote_com
        $insert =  "update user_vote_com 
                    set vote=$vote
                    where user_id=$user_id
                    and com_id=$com_id";
        $stmt = $db->prepare($insert);
        $stmt->execute();
        $stmt->close();
        
        if($vote==1 && $old_vote==-1){
            $update="update com
                     set downvote_count=downvote_count-1,
                     upvote_count=upvote_count+1
                     where com_id=$com_id";
        $stmt = $db->prepare($update);
        $stmt->execute();
            $return['cur_vote'] = 2;
        }
        else if($vote==-1 && $old_vote==1){
            $update="update com
                     set downvote_count=downvote_count+1,
                     upvote_count=upvote_count-1
                     where com_id=$com_id";
        $stmt = $db->prepare($update);
        $stmt->execute();
            $return['cur_vote'] = -2;
        }
        $stmt->close();
    }
}

else{ //else they have NOT previously voted on this comment
    $stmt->close();
    if($vote==1){ //add one to upvote_count
        $update =  "update com 
                    set upvote_count = upvote_count+1
                    where com_id=$com_id";
        $return['cur_vote'] = 1;
    }
    else{ //Add one to downvote_count
        $update =  "update com
                    set downvote_count = downvote_count+1
                    where com_id=$com_id";
        $return['cur_vote'] = -1;
    }
        $stmt = $db->prepare($update);
        $stmt->execute();
        $stmt->close();
        
        //Insert into user_vote_com
        $insert =  "insert into user_vote_com
                    values ($user_id, $com_id, $vote)";
        $stmt = $db->prepare($insert);
        $stmt->execute();
        $stmt->close();
}

        $result['old_vote'] = 5;
$db->close();
echo json_encode($return);
?>
