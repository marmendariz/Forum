<?
/*
    discussion.php
 */
include_once 'lib.php';
require_once 'comment_lib.php';
set_path();
force_ssl();
session_start();
auto_login();

if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}

if (null == ($parent_dis = filter_input(INPUT_GET, 
    dis_id, 
    FILTER_VALIDATE_INT,FILTER_NULL_ON_FAILURE) ) || $_GET['dis_id'] == '0') {
        echo 'Error. Invalid Discussion ID<br>';
        exit;
    }
$logged_in = false;
if(isset($_SESSION['valid_user']))
    $logged_in = true;
$parent_dis = intval(input_clean($_GET['dis_id']));
$dis_id = $parent_dis;

/***/
$nav_items = array();
$nav_ids = array();
$category = "select c.cat_id, c.cat_name 
             from category as c,
             cat_cont_dis as cd
             where cd.cat_id = c.cat_id";
$stmt = $db->prepare($category);
$stmt->execute();
$stmt->bind_result($cat_id, $cat_name);
$stmt->fetch();
echo $cat_name;
$stmt->close();

/***/

/********** Discussion Query ****************/
$dis_query = 'select dis_name, dis_text, user_name, profile_image,
              (ds.upvote_count-ds.downvote_count) as vote_count 
              from discussion as ds,
              user_edit_dis as ue, user as u
              where u.user_id = ue.user_id
              and ue.dis_id = ds.dis_id
              and ds.dis_id = ?';
$dis_stmt = $db->prepare($dis_query);
$dis_stmt->bind_param('i',$parent_dis);
$dis_stmt->execute();
$dis_stmt->store_result();
$dis_stmt->bind_result($dis_name, $dis_text, $dis_usr,$profile_image, $dis_vote);
$dis_stmt->fetch();
/*******************************************/

?>
<!------------------------------ DISCUSSION PAGE  -------------------------------------->
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <link rel='icon' type='image/x-icon' href='img/Q.png'>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <? echo "<title>Quadcore Forum | $dis_name</title>"; ?>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src='js/jquery-1.12.0.min.js' type='text/javascript'></script>
  </head>
  <body>
<? include_once 'header.php';

$bmark = false;

echo "<div class='row'>";
    echo "<div class='large-12 columns medium-12 small-12 small-centered'>";

/************************** PRINT DISCUSSION TOP-SECTION ****************************/

/*********************************************************************************/
        echo "<div class='row panel'>";

            echo "<div class='large-2 medium-1 small-1 columns'>";
                    echo "<div class='row'>";
                        echo "<div class='large-12 medium-3 small-3 columns text-center'>";
                            echo "<h6><b>$dis_usr</b></h6>";
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='row'>";
                        echo "<div class='large-12 columns'>";
                            echo "<img src='$profile_image'>"; 
                        echo "</div>";
                    echo "</div>";
            echo "</div>";


                    /******************************/
echo "<div class='large-9 medium-8 small-8 columns text-left'>";
        
    echo "<div class='row'>";
            echo "<div class='large-12'>";
                echo "<h2>".stripslashes($dis_name)."</h2><hr>";  
                    echo "<h4>".stripslashes($dis_text)."</h4><hr>";
            echo "</div>";
     echo "</div>";

    echo "<div class='row'>";
            echo "<div class='large-10 medium-10 small-8 columns text-left'>";
                echo "<input type='hidden' id='dis_id' value='$dis_id'>";
                if($logged_in)
                echo "<h6><a href='#' class='discussion_reply_link'>Reply</a></h6>";
                else
                echo "<h6><a href='login.php' class='login_reply_link'>Login to Reply</a></h6>";
            echo "</div>";
                    
            if($logged_in){
            echo "<div id ='bookmark' class='large-1 medium-1 small-2 columns text-right bookmark'>";
                echo "<a href='#' class='bookmark_link'><img src='img/Bookmark.png' width='42' height='42'></a>";
            echo "</div>";
            echo "<div id='unbookmark' class='large-1 medium-1 small-2 columns text-right unbookmark'>";
                echo "<a href='#' class='unbookmark_link'><img src='img/Unbookmark.png' width='42' height='42'></a>";
            echo "</div>";
            }
    echo "</div>";

echo "</div>";
                    /******************************************/
                            
            echo "<div class='large-1 medium-2 small-3 columns text-center'>";
                echo "<div class='row'>";
                    echo "<div id='dis_upvote' class='large-12 columns small-centered'>";
                        echo "<a><img src='img/up.png'></a>";
                    echo "</div>";
                echo "</div>";
                echo "<div class='row'>";
                    echo "<div class='large-12 columns small-centered'>";
                        echo "<h4 id='dis_votecount'>$dis_vote</h4>";/*Voting count*/
                    echo "</div>";
                echo "</div>";
                echo "<div class='row'>";
                    echo "<div id='dis_downvote' class='large-12 columns small-centered'>";
                        echo "<a><img src='img/down.png'></a>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";

        echo "</div><br>";

        /*********************************************************************************/
/*************************************************************************************/

/********************************* PRINT OUT COMMENTS ********************************/
$comment_query = 'select * 
                  from dis_cont_com AS d, 
                  com AS c WHERE d.dis_id=? 
                  AND d.com_id=c.com_id';
$stmt = $db->prepare($comment_query);
$stmt->bind_param('i', $parent_dis);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($dis_id, $com_id1, $com_id2,$com_name, $com_level, $com_text, $com_flag, $parent_com_id, $upvote_count, $downvote_count);

echo "<div id='commentArea'>";
    echo "<h4>Comments<h4>";
    echo "<hr id='commentHeading'>";

/* AREA FOR REPLYING DIRECTLY TO DISCUSSION TOP-SECTION */
echo "<div class='discussion_reply'>
     <div class='row'><div class='large-12 columns'>
     <p>Enter your reply:
     <textarea rows='5' id='dis_reply_area'></textarea>
     </p></div></div>
     <div class='row'>
     <div class='large-6 medium-6 small-12 columns'>
     <input type='button' class='button expand dis_comment_submit' value='Submit'>
     </div>
     <div class='large-6 medium-6 small-12 columns'>
     <input type='button' class='button expand alert dis_comment_cancel' value='Cancel'>
     </div></div><hr></div>";
/*********************************************************/

if($logged_in){
    $id = $_SESSION['user_id'];
    echo "<input id='username' type='hidden' value='".$_SESSION['valid_user']."'>";
    echo "<input id='user_id' type='hidden' value='$id'>";
}

$comments = array();

/************* PRINT COMMENTS ************************/
while($stmt->fetch()){
    $usernameQuery = "select u.user_name 
                    from user as u, user_edit_com as e,
                    com as c where c.com_id = ?
                    and e.user_id=u.user_id
                    and c.com_id=e.com_id";
    
    $ustmt = $db->prepare($usernameQuery);
    $ustmt->bind_param('i', $com_id1);
    $ustmt->execute();
    $ustmt->store_result();
    $ustmt->bind_result($username);
    $ustmt->fetch();
    $level2 = false;

    $picquery ="Select profile_image from user where user_name = ?";
    $pstmt=$db->prepare($picquery);
    $pstmt->bind_param('s',$username);
    $pstmt->execute();
    $pstmt->store_result();
    $pstmt->bind_result($pimg);
    $pstmt->fetch();

    $comment = new comment();
    $comment->dis_id = $dis_id;
    $comment->com_id = $com_id2;
    $comment->com_name = $com_name;
    $comment->com_level = $com_level;
    $comment->com_text = $com_text;
    $comment->com_flag = $com_flag;
    $comment->parent_com_id = $parent_com_id;
    $comment->upvote_count = $upvote_count;
    $comment->downvote_count = $downvote_count;
    $comment->username = $username;
    $comment->pimg = $pimg;
    
    $comments[] = $comment;
    $ustmt->close();
    $pstmt->close();
}

$l1comments = array();
$l2comments = array();

foreach($comments as $c){
    if($c->com_level == 1)
        $l1comments[] = $c;
    else
        $l2comments[] = $c;
}

foreach($l1comments as $c1){
    print_comment($db,$logged_in, $c1->dis_id, $c1->com_id, 
                  $c1->com_name, $c1->com_level,$c1->com_text, 
                  $c1->com_flag, $c1->parent_com_id, 
                  $c1->upvote_count, $c1->downvote_count, 
                  $c1->username, $c1->pimg);
    foreach($l2comments as $c2){
        if($c2->parent_com_id == $c1->com_id){
            print_comment($db,$logged_in, $c2->dis_id, $c2->com_id, 
                  $c2->com_name, $c2->com_level,$c2->com_text, 
                  $c2->com_flag, $c2->parent_com_id, 
                  $c2->upvote_count, $c2->downvote_count, 
                  $c2->username, $c2->pimg);
        }
    }
}
    echo "</div>";


    echo "</div>";
/*********** END COMMENT PRINT LOOP  *********************/

/****************************************************************/

/*********** SEE IF ALREADY BOOKMARKED *************************/


if($logged_in){
    
    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }
        $user_name = input_clean($_SESSION['valid_user']);

        $userQuery = "select user_id 
                    from user
                    where user_name=?";
        $ustmt = $db->prepare($userQuery);
        $ustmt->bind_param('s', $user_name);
        $ustmt->execute();
        $ustmt->store_result();
        $ustmt->bind_result($user_id);
        $ustmt->fetch();

        $bookmarkQ = "select user_id, dis_id 
                    from bookmarked 
                    where user_id=? and dis_id=?";
        $bstmt = $db->prepare($bookmarkQ);
        $bstmt->bind_param('ii', $user_id, $dis_id);
        $bstmt->execute();
        $bstmt->store_result();
        $brows = $bstmt->num_rows();
        $bstmt->bind_result($b_user_id, $b_dis_id);
        if($brows != 0)
            $bmark=true;
        else
            $bmark=false;

$bstmt->close();
$ustmt->close();
}

$dis_stmt->close();
$stmt->close();
$db->close();
?>
  <!-------------------------------------------->
  </div>
</div>
<!-------------------------------------------------------------------->    
    <script src="js/vendor/jquery.js"></script>
    <script src="js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script src="js/foundation.min.js"></script>
    <script>
/***********************************************************************************/
/********************************** SCRIPTS ****************************************/
/***********************************************************************************/
$(document).foundation();
/***/

var parent_com_id = 0;
var com_id = 0;
var oldCom;
var oldComText;

$(document).ready(function(){
    
/*********************Bookmark*********************************************/
    var bookmark = '<?php echo $bmark; ?>';    
    if(bookmark == true){
        $('#bookmark').hide();
        $('#unbookmark').show();
    }
    else{
        $('#unbookmark').hide();
        $('#bookmark').show();
    }

    $('body').on('click','.bookmark_link',function(e){
        e.preventDefault();
            var username = $('#username').val();
            var userId = $('#user_id').val();
            var disId = $('#dis_id').val();
            $('.bookmark').css("visibility", "hidden");
            $('.bookmark').css("display", "none");
            $('.unbookmark').css("display", "block");
            $('.unbookmark').css("visibility", "visible");
        
        $.post('post_bookmark.php', {username: username, user_id: userId, dis_id: disId}) 
         
    });
    
    $('body').on('click','.unbookmark_link',function(e){
        e.preventDefault();
            var username = $('#username').val();
            var userId = $('#user_id').val();
            var disId = $('#dis_id').val();
            $('.unbookmark').css("visibility", "hidden");
            $('.unbookmark').css("display", "none");
            $('.bookmark').css("display", "block");
            $('.bookmark').css("visibility", "visible");
            
        $.post('delete_bookmark.php', {username: username, user_id: userId, dis_id: disId}) 
    
    });


    /******************** CREATE TEXTAREA FOR COMMENTING  **********************/
    $('body').on('click','.comment_reply_link',function(e){
        e.preventDefault();
        $('.comment_reply').css("display","none");
        $('.comment_reply').css("visibility","hidden");
        
        $('.discussion_reply').css('visibility', 'hidden');
        $('.discussion_reply').css('display', 'none');
        $('.discussion_reply').val('');
        
        var replyArea = "<div class='comment_reply'>"+
                         "<div class='row'><div class='large-12 columns'>"+
                         "<hr><p>Enter your reply:<textarea rows='5'></textarea>"+
                         "</p></div></div>"+
                         "<div class='row'>"+
                         "<div class='large-6 small-12 medium-6 columns'>"+
                         "<input type='button' class='button expand comment_submit' value='Submit'>"+
                         "</div>"+
                         "<div class='large-6 medium-6 small-12 columns'>"+
                         "<input type='button' class='button expand alert comment_cancel' value='Cancel'>"+
                         "</div></div><hr></div>";

        var $reply = $(replyArea);
        var $temp  = $(this).parent().parent().parent().parent().parent();
        $temp.after($reply);
        $reply.css("display","inline");
        $reply.css("visibility","visible");

        var width = $(window).width();
        var height = $(window).height();
        if((width <= 1023) && (height<=768))
            $('html, body').animate({scrollTop: $reply.offset().top});
        else
            $('html, body').animate({scrollTop: $reply.offset().top-300});
        $reply.find('textarea').focus();

        parent_com_id = $temp.find('.parent_com_id').val();
        com_id = $temp.find('.com_id').val();
        //var comment = $(this).parent().parent().parent().parent();
        //com_id = comment.find('.com_id').val();
        //alert(com_id);
    });
    /***************************************************************************/ 

    /*************************** POST/SAVE COMMENT ****************************/    
    $('body').on('click','.comment_submit' ,function(e){
        var $newComment = $(this).parent().parent().prev().find('textarea');
        var username = $('#username').val();
        var userId = $('#user_id').val();
        var disId = $('#dis_id').val();
        
        var commentPost = "<div class='row comment'>"+ //1
                            "<div class='panel large-10 medium-10 small-10 columns innerdiv small-centered right'>"+ //2
                            "<div class='row'>"+ //3
                            "<div class='columns large-2 medium-2 small-3 text-center small-centered large-uncentered medium-uncentered'>"+ //4
                                "<div class='row'>"+ //5
                                    "<div class='large-12 medium-12 small-12 text-center columns small-centered large-uncentered medium-uncentered'>"+ //6
                                        "<h6 class='username'><b></b></h6>"+
                                    "</div>"+ //6
                                    "</div>"+ //5
                                "<div class='row'>"+ //7
                                "<div class='large-12 medium-12 small-12 text-center columns show-for-medium-up'>"+ //8
                                    "<img class='profile_image'>"+
                                "</div>"+ //8
                                "</div>"+ //7
                                "</div>"+ //4
                                "<div class='columns large-9 medium-8 small-9'>"+ //9
                                "<input type='hidden' class='com_level' value='2'>"+
                                "<input type='hidden' class='parent_com_id' value='"+com_id+"'>"+ /*Parent com id needs to be set*/
                                "<input type='hidden' class='com_id'><hr>"+ /*Com id needs to be set*/
                                "<p></p><hr>"+
                                "<div class='row com_links text-center'>"+ //10
                                    "<div class='columns large-4 medium-4 small-4'>"+ //11
                                        "<h6><a class='comment_reply_link'>Reply</a></h6>"+
                                    "</div>"+ //11
                                    "<div class='columns large-4 medium-4 small-4'>"+ //12
                                        "<h6><a class='comment_edit_link'>Edit</a></h6>"+
                                    "</div>"+ //12
                                    "<div class='columns large-4 medium-4 small-4'>"+ //13
                                        "<h6><a class='comment_delete_link'>Delete</a></h6>"+
                                    "</div>"+//13
                                    "</div>"+//10
                                    "</div>"+//9
                                    "<div class='large-1 medium-2 small-3 columns text-center'>"+
                                    "<div class='row'>"+
                                    "<div class='large-12 medium-8 small-12 columns small-centered'>"+
                                    "<a class='up_vote'><img src='img/up.png'></a>"+
                                    "</div>"+
                                    "</div>"+
                                    "<div class='row'>"+
                                    "<div class='large-12 medium-8 small-12 columns small-centered'>"+
                                    "<h6 class='vote_count'>0</h6>"+
                                    "</div>"+
                                    "</div>"+
                                    "<div class='row'>"+
                                    "<div class='large-12 medium-8 small-12 columns small-centered'>"+
                                    "<a class='down_vote'><img src='img/down.png'></a>"+
                                    "</div>"+
                                    "</div>"+
                                    "</div>"+
                                    "</div>"+//3
                                "</div>"+//2
                            "</div>"; //1
        
        var $post = $(commentPost);

        var level  = $('#'+com_id).val();
        if(level==2){
           // $post.children().first().addClass('right');
        }

        var commentText = $newComment.val();
        /*Append comment to page*/
        $post.find('.username').find('b').text(username);

        //AJAX for posting comment to page
        $.post('post_comment.php', {username: username, commentText: commentText, 
                                    user_id: userId, dis_id: disId, com_id: com_id, 
                                    parent_com_id: parent_com_id }, 
            function(result){
                result = JSON.parse(result);
                $post.find('.innerdiv').find('p').text(result.commentText);
                $post.find('.com_id').val(result.com_id);
                $post.find('.profile_image').attr('src', result.image_url);
            });

            $(this).parent().parent().parent().parent().parent().after($post);
            $('.comment_reply').remove();
            parent_com_id = 0;
            com_id = 0;
    });
    /************************************************************************/

    /**************** CANCEL COMMENTING ****************/ 
    $('body').on('click','.comment_cancel', function(e){
        $('.comment_reply').css("display","none");
        $('.comment_reply').css("visibility","hidden");
        
        $('.discussion_reply').css('visibility', 'hidden');
        $('.discussion_reply').css('display', 'none');
        $('.discussion_reply').val('');
        
        $newComment.val('');
        this.remove();
        parent_com_id = 0;
        com_id = 0;
    });
    /**************************************************/

    /************** CLICK TO REPLY TO DISCUSSION **************/
    $('body').on('click','.discussion_reply_link', function(e){
        $('.discussion_reply').css('visibility', 'visible');
        $('.discussion_reply').css('display', 'inline');
        $('#dis_reply_area').focus();
        $('.comment_reply').remove();
    });
    /*********************************************************/

    /************ SUBMIT REPLY TO MAIN DISCUSSION *************/
    $('body').on('click','.dis_comment_submit', function(e){
        $('.discussion_reply').css('visibility', 'hidden');
        $('.discussion_reply').css('display', 'none');
        
        var $newComment = $('#dis_reply_area');
        var username = $('#username').val();

        
        var commentPost = "<div class='row comment'>"+ //1
                            "<div class='panel large-10 medium-12 small-12 columns innerdiv small-centered'>"+ //2
                            "<div class='row'>"+ //3
                            "<div class='columns large-2 medium-2 small-3 text-center small-centered large-uncentered medium-uncentered'>"+ //4
                                "<div class='row'>"+ //5
                                    "<div class='large-12 medium-12 small-12 text-center columns small-centered large-uncentered'>"+ //6
                                        "<h6 class='username'><b>"+username+"</b></h6>"+
                                    "</div>"+ //6
                                    "</div>"+ //5
                                "<div class='row'>"+ //7
                                "<div class='large-12 medium-12 small-12 text-center columns small-centered large-uncentered show-for-medium-up'>"+ //8
                                    "<img class='profile_image'>"+
                                "</div>"+ //8
                                "</div>"+ //7
                                "</div>"+ //4
                                "<div class='columns large-9 medium-8 small-9'>"+ //9
                                "<input type='hidden' class='com_level' value='1'>"+
                                "<input type='hidden' class='parent_com_id' value='1'>"+
                                "<input type='hidden' class='com_id'><hr>"+ /*Com id needs to be set*/
                                "<p class='comment_text'></p><hr>"+
                                "<div class='row com_links text-center'>"+ //10
                                    "<div class='columns large-4 medium-4 small-4'>"+ //11
                                        "<h6><a class='comment_reply_link'>Reply</a></h6>"+
                                    "</div>"+ //11
                                    "<div class='columns large-4 medium-4 small-4'>"+ //12
                                        "<h6><a class='comment_edit_link'>Edit</a></h6>"+
                                    "</div>"+ //12
                                    "<div class='columns large-4 medium-4 small-4'>"+ //13
                                        "<h6><a class='comment_delete_link'>Delete</a></h6>"+
                                    "</div>"+//13
                                    "</div>"+//10
                                    "</div>"+//9
                                    "<div class='large-1 medium-2 small-3 columns text-center'>"+
                                    "<div class='row'>"+
                                    "<div class='large-12 medium-8 small-12 columns small-centered'>"+
                                    "<a class='up_vote'><img src='img/up.png'></a>"+
                                    "</div>"+
                                    "</div>"+
                                    "<div class='row'>"+
                                    "<div class='large-12 medium-8 small-12 columns small-centered'>"+
                                    "<h6 class='vote_count'>0</h6>"+
                                    "</div>"+
                                    "</div>"+
                                    "<div class='row'>"+
                                    "<div class='large-12 medium-8 small-12 columns small-centered'>"+
                                    "<a class='down_vote'><img src='img/down.png'></a>"+
                                    "</div>"+
                                    "</div>"+
                                    "</div>"+
                                    "</div>"+//3
                                "</div>"+//2
                            "</div>"; //1

        var $post = $(commentPost); 
        var commentText = $newComment.val();


        var userId = $('#user_id').val();
        var disId = $('#dis_id').val();

        
        $.post('dis_post_comment.php', {username: username, commentText: commentText, 
                                        user_id: userId, dis_id: disId }, 
            function(result){
                result = JSON.parse(result);
                $post.find('.innerdiv').find('p').text(result.text);
                $post.find('.com_id').val(result.com_id);
                $post.find('.profile_image').attr('src', result.image_url);
            });
        
        $(this).parent().parent().parent().after($post);
        $('#dis_reply_area').val('');
    });
    /*********************************************************/
    
    /************ CANCEL REPLY TO MAIN DISCUSSION *************/
    $('body').on('click','.dis_comment_cancel', function(e){
        $('.discussion_reply').css('visibility', 'hidden');
        $('.discussion_reply').css('display', 'none');
        $(this).parent().parent().parent().find('textarea').val('');
    });
    /*********************************************************/


    /******************** UPVOTE FUNCTION *********************/
    $('body').on('click', '.up_vote', function(e){
        var element = $(this).parent().parent().parent().parent();
        var com_id = element.find('.com_id').val();
        var count_html = element.find('.vote_count');
        var user_id = $('#user_id').val();
        
        $.post('comment_vote.php', {user_id: user_id, com_id: com_id, vote: 1 }, 
            function(result){
                result = JSON.parse(result);
                var vote = result.cur_vote;
                var count = parseInt(count_html.text())+vote;
                count_html.html("<b>"+count+"</b>");
            });
    });
    /**********************************************************/
    /******************* DOWNVOTE FUNCTION *********************/
    $('body').on('click', '.down_vote', function(e){
        var element = $(this).parent().parent().parent().parent();
        var com_id = element.find('.com_id').val();
        var count_html = element.find('.vote_count');
        var user_id = $('#user_id').val();
        
        $.post('comment_vote.php', {user_id: user_id, com_id: com_id, vote: -1 }, 
            function(result){
                result = JSON.parse(result);
                var vote = result.cur_vote;
                var count = parseInt(count_html.text())+vote;
                count_html.html("<b>"+count+"</b>");
            });
    });
    /**********************************************************/

    
    /************** DISCUSSION UPVOTE FUNCTION *****************/
    $('body').on('click', '#dis_upvote', function(e){
        var element = $(this).parent().parent().parent().parent();
        var dis_id = $('#dis_id').val();
        var count_html = $('#dis_votecount');
        var user_id = $('#user_id').val();
        
        $.post('discussion_vote.php', {user_id: user_id, dis_id: dis_id, vote: 1 }, 
            function(result){
                result = JSON.parse(result);
                var vote = result.cur_vote;
                var count = parseInt(count_html.text())+vote;
                count_html.html("<b>"+count+"</b>");
            });
    });
    /**********************************************************/
    /************* DISCUSSION DOWNVOTE FUNCTION ***************/
    $('body').on('click', '#dis_downvote', function(e){
        var element = $(this).parent().parent().parent().parent();
        var dis_id = $('#dis_id').val();
        var count_html = $('#dis_votecount');
        var user_id = $('#user_id').val();
        
        
        $.post('discussion_vote.php', {user_id: user_id, dis_id: dis_id, vote: -1 }, 
            function(result){
                result = JSON.parse(result);
                var vote = result.cur_vote;
                var count = parseInt(count_html.text())+vote;
                count_html.html("<b>"+count+"</b>");
            });
    });
    /**********************************************************/


    /**********************************************************/
    $('body').on('click', '.comment_edit_link', function(e){
        $('.comment_edit_area').remove();
        var comment = $(this).parent().parent().parent().parent();
        oldComText = comment.find('p').text();
        oldCom = comment;
        var text = comment.find('.comment_text').text();
        
        var editArea = "<div class='comment_edit_area'>"+
                            "<div class='row'>"+
                                "<div class='large-12 columns'>"+
                                    "<p>Edit your comment:<textarea id='editArea' rows='5'>"+text+"</textarea>"+
                                    "</p>"+
                                "</div>"+
                            "</div>"+
                            "<div class='row'>"+
                                "<div class='large-6 small-12 medium-6 columns'>"+
                                    "<input type='button' class='button expand comment_edit_submit' value='Submit'>"+
                                "</div>"+
                                "<div class='large-6 medium-6 small-12 columns'>"+
                                    "<input type='button' class='button expand alert comment_edit_cancel' value='Cancel'>"+
                                "</div>"+
                            "</div>"+
                        "</div>";
        comment.find('.comment_text').replaceWith(editArea);
    });


    $('body').on('click', '.comment_edit_cancel', function(e){
        $('.comment_edit_area').replaceWith("<p class='comment_text'>"+oldComText+"</p>");
    });


    $('body').on('click', '.comment_edit_submit', function(e){
        var user_id = $('#user_id').val();
        var com_id = oldCom.find('.com_id').val();
        
        var newText = $('#editArea').val();
        $.post('edit_comment.php', {user_id: user_id, com_id: com_id, 
                                       text: newText }, 
            function(result){
                var result = JSON.parse(result);
                $('.comment_edit_area').replaceWith("<p class='comment_text'>"+result.text+"</p>");
            });
    });
    /**********************************************************/


});
/********************************************************************************************/
/********************************************************************************************/
/********************************************************************************************/
    </script>
  </body>
</html>
