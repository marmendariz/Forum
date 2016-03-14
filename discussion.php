<?
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

<!---------------------- DISCUSSION PAGE------------------------------>
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

$parent_dis = input_clean($_GET['dis_id']);
$dis_query = 'select dis_name, dis_text from discussion where dis_id = ?';
$query = 'select * from dis_cont_com AS d, com AS c WHERE d.dis_id=? AND d.com_id=c.com_id';
$stmt = $db->prepare($query);
$stmt->bind_param('i', $parent_dis);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($dis_id, $com_id1, $com_id2,$com_name, $com_level, $com_text, $com_flag, $parent_com_id, $upvote_count, $downvote_count);

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
echo "</div>";
echo "</div>";

echo "<h4>Comments<h4><hr>"; 
while($stmt->fetch()){

    $logged_in = false;
    if(isset($_SESSION['valid_user']))
        $logged_in = true;
    $user = $_SESSION['valid_user'];
    echo "<input id='username' type='hidden' value='$user'>";

    echo "<div class='row'>";
    echo "<div class='columns large-12 panel'>";
    //echo "<h5 style='color:#008cbb;'>$com_name<h5>";

    echo "<h6>Username</h6>";
    echo "<hr>";

    echo "<p>&nbsp &nbsp &nbsp &nbsp$com_text</p>";
    if($logged_in)
        echo "<h6><a href='#' class='comment_reply_link'>Reply</a></h6>";
    else
        echo "<h6><a href='login.php' class='login_reply_link'>Login to Reply</a></h6>";
    echo "</div>";
    echo "</div>";

    if($logged_in){
    echo "<div class='comment_reply'>";
        echo "<div class='row'>";
            echo "<div class='large-12 columns'>";
                    echo "<hr>";
                    echo "<p>Enter your reply:";
                    echo "<textarea rows='5'></textarea>";
                    echo "</p>";
            echo "</div>";
        echo "</div>";
   
        echo "<div class='row'>";
            echo "<div class='large-6 columns'>";
                echo "<input type='button' class='button expand comment_submit' value='Submit'>";
            echo "</div>";
    
            echo "<div class='large-6 columns'>";
                echo "<input type='button' class='button expand alert comment_cancel' value='Cancel'>";
            echo "</div>";
            echo "</div>";

    echo "<hr>";
    echo "</div>";
    }
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
        $(document).foundation();
$(document).ready(function(){

    $('.comment_reply_link').on('click',function(e){
        e.preventDefault();
        $('.comment_reply').css("display","none");
        $('.comment_reply').css("visibility","hidden");
        var $area = $(this).parent().parent().parent().next();
        $area.css("display","inline");
        $area.css("visibility","visible");

        var width = $(window).width();
        var height = $(window).height();
        if((width <= 1023) && (height<=768))
            $('html, body').animate({scrollTop: $area.offset().top});
        else
            $('html, body').animate({scrollTop: $area.offset().top-300});
        $area.find('textarea').focus();
    });

    $('.comment_submit').on('click', function(e){
        var $newComment = $(this).parent().parent().prev().find('textarea');
        var username = $('#username').val();
        var replyArea = "<div class='comment_reply'><div class='row'><div class='large-12 columns'>"+
                        "<hr><p>Enter your reply:<textarea rows='5'></textarea></p>"+
                        "</div></div>";
        var commentPost = "<div class='row'><div class='panel large-12 columns innerdiv'>"+
                            "<h6 class='username'></h6><hr><p></p><h6>"+
                            "<a href='#' class='comment_reply_link'>Reply</a></h6></div></div>";
        var $post = $(commentPost);
        //var $reply = $(replyArea);
        $post.find('.username').text(username);
        $post.find('.innerdiv').find('p').text($newComment.val());
        $(this).parent().parent().parent().after($post);
        //$(this).parent().parent().parent().next().after($reply);
        $('.comment_reply').css("display","none");
        $('.comment_reply').css("visibility","hidden");
        $newComment.val('');
    });

    $('.comment_cancel').on('click', function(e){
        $('.comment_reply').css("display","none");
        $('.comment_reply').css("visibility","hidden");
        $newComment.val('');
    });



        });
    </script>
  </body>
</html>
