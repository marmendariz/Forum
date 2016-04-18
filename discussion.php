<?
/*
    discussion.php
 */
include_once 'lib.php';
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

/********** Discussion Query ****************/
$dis_query = 'select dis_name, dis_text 
              from discussion 
              where dis_id = ?';
$dis_stmt = $db->prepare($dis_query);
$dis_stmt->bind_param('i',$parent_dis);
$dis_stmt->execute();
$dis_stmt->store_result();
$dis_stmt->bind_result($dis_name, $dis_text);
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

/************************** PRINT DISCUSSION TOP-SECTION ****************************/
echo "<div class='row'>";
    echo "<div class='large-12 large-centered columns medium-12 medium-centered small-12 small-centered'>";
        echo "<div class='row'>";
            echo "<div class='panel large-12 columns'>";
                echo "<h1>$dis_name<h1><hr>";  
                    echo "<h3>$dis_text<h3><hr>";
                    echo "<input type='hidden' id='dis_id' value='$dis_id'>";
                    if($logged_in)
                        echo "<h6><a href='#' class='discussion_reply_link'>Reply</a></h6>";
                    else
                        echo "<h6><a href='login.php' class='login_reply_link'>Login to Reply</a></h6>";
            echo "</div>";
        echo "</div>";
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

/***************************************** PRINT COMMENTS LOOP *********************************/
while($stmt->fetch()){
    $usernameQuery = "select user_name 
                    from user 
                    natural join user_edit_com 
                    natural join com 
                    where com_id = $com_id1";
    $ustmt = $db->prepare($usernameQuery);
    $ustmt->execute();
    $ustmt->store_result();
    $ustmt->bind_result($username);
    $ustmt->fetch();
    $level2 = false;

   echo "<div class='row comment'>"; /*********************/
   if($com_level == 2){
       $level2 = true;
        echo "<div class='columns large-10 medium-10 small-10 panel right'>";
    }
    else
        echo "<div class='columns large-10 medium-12 small-12 panel small-centered'>";

   echo "<div class='row'>";
   /*****/
   echo "<div class='columns large-2 medium-2 small-3 small-centered large-uncentered medium-uncentered text-center'>";
                echo "<div class='row'>";
                    echo "<div class='large-12 medium-12 small-12 columns text-center large-uncentered medium-uncentered small-centered'>";
                        echo "<h6 class='text-center'><b>$username</b></h6>";
                    echo "</div>";
                echo "</div>";
                echo "<div class='row'>";
                    echo "<div class='large-12 medium-12 show-for-medium-up columns'>";
                        echo "<img class='user_comment_info' src='img/bleh.gif'>";
                    echo "</div>";
                echo "</div>";
    echo "</div>";
    /*****/
            
    echo "<div class='columns large-9 medium-8 small-9'>";
        echo "<input class='com_level' id='$com_id1' type='hidden' value='$com_level'>"; /*COM LEVEL*/
        echo "<input class='parent_com_id' type='hidden' value='$parent_com_id'>";
        echo "<input class='com_id' type='hidden' value='$com_id1'>";
        echo "<hr>";
        echo "<p>$com_text</p>";
        echo "<hr>";

    /******* Comment links  *********/
    echo "<div class='row com_links text-center'>"; 
        echo "<div class='columns large-4 medium-4 small-4'>";
            if($logged_in)
                echo "<h6><a href='#' class='comment_reply_link'>Reply</a></h6>";
            else
                echo "<h6><a href='login.php' class='login_reply_link'>Login to Reply</a></h6>";
        echo "</div>";

        if($username === $_SESSION['valid_user']){
            echo "<div class='columns large-4 medium-4 small-4'>";
                echo "<h6><a href='#' class='comment_edit_link'>Edit</a></h6>";
            echo "</div>";
        
            echo "<div class='columns large-4 medium-4 small-4'>";
                echo "<h6><a href='#' class='comment_delete_link'>Delete</a></h6>";
            echo "</div>";
        }
        echo "</div>";
    echo "</div>";
    /****** End Comment Links  *****/

    /************* UP/DOWN VOTE SECTION *******************/ 
        echo "<div class='large-1 medium-2 small-3 columns text-center'>";
        //echo '<br>';
        echo "<div class='row'>";
            echo "<div class='large-12 medium-12 small-12 columns'>";
                echo "<a><img src='img/up.png'></a>";
            echo "</div>";
        echo "</div>";

      echo "<div class='row'>";
            echo "<div class='large-12 medium-12 small-12 columns'>";
                echo "<h6>0</h6>";
            echo "</div>";
      echo "</div>";

        echo "<div class='row'>";
            echo "<div class='large-12 medium-12 small-12 columns'>";
                echo "<a><img src='img/down.png'></a>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
    /********************************************************/

    echo "</div>";
    echo "</div>";
    /*******************************************************/
    echo "</div>";
}
/*********** END COMMENT PRINT LOOP  *********************/
echo "</div>";
/****************************************************************/

$dis_stmt->close();
$stmt->close();
$ustmt->close();
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
$(document).ready(function(){

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
                                    "<img class='user_comment_info' src='img/bleh.gif'>"+
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
                                        "<h6><a href='#' class='comment_reply_link'>Reply</a></h6>"+
                                    "</div>"+ //11
                                    "<div class='columns large-4 medium-4 small-4'>"+ //12
                                        "<h6><a href='#' class='comment_edit_link'>Edit</a></h6>"+
                                    "</div>"+ //12
                                    "<div class='columns large-4 medium-4 small-4'>"+ //13
                                        "<h6><a href='#' class='comment_delete_link'>Delete</a></h6>"+
                                    "</div>"+//13
                                    "</div>"+//10
                                    "</div>"+//9
                                    "<div class='large-1 medium-2 small-3 columns'>"+ //14
                                        "<h6><a>UP</a></h6>"+
                                        "<h6><a>DOWN</a></h6>"+
                                    "</div>"+ //14
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
                                        "<h6 class='username'><b></b></h6>"+
                                    "</div>"+ //6
                                    "</div>"+ //5
                                "<div class='row'>"+ //7
                                "<div class='large-12 medium-12 small-12 text-center columns small-centered large-uncentered show-for-medium-up'>"+ //8
                                    "<img class='user_comment_info' src='img/bleh.gif'>"+
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
                                        "<h6><a href='#' class='comment_reply_link'>Reply</a></h6>"+
                                    "</div>"+ //11
                                    "<div class='columns large-4 medium-4 small-4'>"+ //12
                                        "<h6><a href='#' class='comment_edit_link'>Edit</a></h6>"+
                                    "</div>"+ //12
                                    "<div class='columns large-4 medium-4 small-4'>"+ //13
                                        "<h6><a href='#' class='comment_delete_link'>Delete</a></h6>"+
                                    "</div>"+//13
                                    "</div>"+//10
                                    "</div>"+//9
                                    "<div class='large-1 medium-2 small-3 columns'>"+ //14
                                        "<h6><a>UP</a></h6>"+
                                        "<h6><a>DOWN</a></h6>"+
                                    "</div>"+ //14
                                    "</div>"+//3
                                "</div>"+//2
                            "</div>"; //1

        var $post = $(commentPost); 
        var commentText = $newComment.val();
        
        /*Append comment to page*/
        $post.find('.username').find('b').text(username);
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
