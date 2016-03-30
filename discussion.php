<?
/*
    disussion.php
 */
include_once 'lib.php';
set_path();
force_ssl();
session_start();
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <link rel='icon' type='image/x-icon' href='img/Q.png'>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Account Creation</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src='js/jquery-1.12.0.min.js' type='text/javascript'></script>
  </head>
  <body>

<? include_once 'header.php'; ?>

<!---------------------- DISCUSSION PAGE ------------------------------>
<div class='row'>
<div class='large-12 large-centered columns medium-7 medium-centered small-10 small-centered'>
  <!-------------------------------------------->
<?php
if(!($db = db_connect())){
    echo "Database error<br>";
    exit;
}

if (null == ($parent_dis = filter_input(INPUT_GET, dis_id, FILTER_VALIDATE_INT,FILTER_NULL_ON_FAILURE) ) || $_GET['dis_id'] == '0') {
    echo 'Error. Invalid Discussion ID<br>';
}

$logged_in = false;
if(isset($_SESSION['valid_user']))
    $logged_in = true;
$parent_dis = intval(input_clean($_GET['dis_id']));

/********************* PRINT OUT DISCUSSION TOP SECTION ******************/
$dis_query = 'select dis_name, dis_text from discussion where dis_id = ?';
$dis_stmt = $db->prepare($dis_query);
$dis_stmt->bind_param('i',$parent_dis);
$dis_stmt->execute();
$dis_stmt->store_result();
$dis_stmt->bind_result($dis_name, $dis_text);
$dis_stmt->fetch();

echo "<div class='row'>";
echo "<div class='panel large-12 columns'>";
echo "<h1>$dis_name<h1><hr>";  
echo "<h3>$dis_text<h3><hr>";
if($logged_in)
    echo "<h6><a href='#' class='discussion_reply_link'>Reply</a></h6>";
else
    echo "<h6><a href='login.php' class='login_reply_link'>Login to Reply</a></h6>";
echo "</div>";
echo "</div>";
/**************************************************************************/

/********************** PRINT OUT COMMENTS *******************/
$comment_query = 'select * from dis_cont_com AS d, com AS c WHERE d.dis_id=? AND d.com_id=c.com_id';
$stmt = $db->prepare($comment_query);
$stmt->bind_param('i', $parent_dis);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($dis_id, $com_id1, $com_id2,$com_name, $com_level, $com_text, $com_flag, $parent_com_id, $upvote_count, $downvote_count);

echo "<div id='commentArea'>";
echo "<h4>Comments<h4><hr id='commentHeading'>";

/* AREA FOR REPLYING DIRECTLY TO DISCUSSION TOP-SECTION */
echo "<div class='discussion_reply'>
     <div class='row'><div class='large-12 columns'>
     <p>Enter your reply:
     <textarea rows='5' id='dis_reply_area'></textarea>
     </p></div></div>
     <div class='row'>
     <div class='large-6 columns'>
     <input type='button' class='button expand dis_comment_submit' value='Submit'>
     </div>
     <div class='large-6 columns'>
     <input type='button' class='button expand alert dis_comment_cancel' value='Cancel'>
     </div></div><hr></div>";

while($stmt->fetch()){
    $user = $_SESSION['valid_user'];
    echo "<div class='row comment'>";
    echo "<div class='columns large-12 panel'>";

    echo "<h6>Username</h6>";
    echo "<input id='username' type='hidden' value='$user'>";
    echo "<hr>";
    echo "<p>$com_text</p>";
    if($logged_in)
        echo "<h6><a href='#' class='comment_reply_link'>Reply</a></h6>";
    else
        echo "<h6><a href='login.php' class='login_reply_link'>Login to Reply</a></h6>";

    echo "</div>";
    echo "</div>";
}
echo "</div>";
/****************************************************************/

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
/******************************** SCRIPTS ******************************************/
/***********************************************************************************/
$(document).foundation();
/***/
$(document).ready(function(){

    /******************** CREATE TEXTAREA FOR COMMENTING  **********************/
    $('body').on('click','.comment_reply_link',function(e){
        e.preventDefault();
        $('.comment_reply').css("display","none");
        $('.comment_reply').css("visibility","hidden");
        
       var replyArea = "<div class='comment_reply'>"+
                         "<div class='row'><div class='large-12 columns'>"+
                         "<hr><p>Enter your reply:<textarea rows='5'></textarea>"+
                         "</p></div></div>"+
                         "<div class='row'>"+
                         "<div class='large-6 columns'>"+
                         "<input type='button' class='button expand comment_submit' value='Submit'>"+
                         "</div>"+
                         "<div class='large-6 columns'>"+
                         "<input type='button' class='button expand alert comment_cancel' value='Cancel'>"+
                         "</div></div><hr></div>";
        var $reply = $(replyArea);
        $(this).parent().parent().parent().after($reply);
        $reply.css("display","inline");
        $reply.css("visibility","visible");

        var width = $(window).width();
        var height = $(window).height();
        if((width <= 1023) && (height<=768))
            $('html, body').animate({scrollTop: $reply.offset().top});
        else
            $('html, body').animate({scrollTop: $reply.offset().top-300});
        $reply.find('textarea').focus();
    });
    /***************************************************************************/ 

    /*************************** POST/SAVE COMMENT ****************************/    
    $('body').on('click','.comment_submit' ,function(e){
        var $newComment = $(this).parent().parent().prev().find('textarea');
        var username = $('#username').val();
        var commentPost = "<div class='row'>"+
                          "<div class='panel large-12 columns innerdiv'>"+
                          "<h6 class='username'></h6><hr>"+
                          "<p></p>"+
                          "<h6><a href='#' class='comment_reply_link'>Reply</a></h6>"+
                          "</div></div>";
        var $post = $(commentPost); 
        var commentText = $newComment.val();
        
        /*Append comment to page*/
        $post.find('.username').text(username);
        $post.find('.innerdiv').find('p').text($newComment.val());
        $(this).parent().parent().parent().after($post);

        $('.comment_reply').css("display","none");
        $('.comment_reply').css("visibility","hidden");
        $newComment.val('');

        /*AJAX for posting comment to page*/
        $.post('post_comment.php', {username: username, commentText: commentText }, function(result){
            //alert(result);
        });
    });
    /************************************************************************/

    /**************** CANCEL COMMENTING ****************/ 
    $('body').on('click','.comment_cancel', function(e){
        $('.comment_reply').css("display","none");
        $('.comment_reply').css("visibility","hidden");
        $newComment.val('');
        this.remove();
    });
    /**************************************************/

    /************** CLICK TO REPLY TO DISCUSSION **************/
    $('body').on('click','.discussion_reply_link', function(e){
        $('.discussion_reply').css('visibility', 'visible');
        $('.discussion_reply').css('display', 'inline');
        $('#dis_reply_area').focus();
    });
    /*********************************************************/

    /************ SUBMIT REPLY TO MAIN DISCUSSION *************/
    $('body').on('click','.dis_comment_submit', function(e){
        $('.discussion_reply').css('visibility', 'hidden');
        $('.discussion_reply').css('display', 'none');
        
        var $newComment = $('#dis_reply_area');
        
        var username = $('#username').val();
        var commentPost = "<div class='row'>"+
                          "<div class='panel large-12 columns innerdiv'>"+
                          "<h6 class='username'></h6><hr>"+
                          "<p></p>"+
                          "<h6><a href='#' class='comment_reply_link'>Reply</a></h6>"+
                          "</div></div>";
        var $post = $(commentPost); 
        var commentText = $newComment.val();
        
        /*Append comment to page*/
        $post.find('.username').text(username);
        $post.find('.innerdiv').find('p').text($newComment.val());
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
});
/********************************************************************************************/
/********************************************************************************************/
/********************************************************************************************/
    </script>
  </body>
</html>
