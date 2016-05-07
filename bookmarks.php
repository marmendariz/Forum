<?
include_once 'lib.php';
set_path();
force_ssl();
session_start();
auto_login();
?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <link rel='icon' type='image/x-icon' href='img/Q.png' /> 
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quadcore Forum | Profile</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/quadcore.css" />
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>

<?php
include_once 'header.php';

$login_failed = false;

/**********************************************/
/*IF VALID LOGIN STILL ACTIVE, DISPLAY MESSAGE*/
if(isset($_SESSION['valid_user'])){
?>
    <!---<div class='large-7 columns panel large-centered text-center medium-7 medium-centered small-10 small-centered'>-->
<?php
    

    if(!($db = db_connect())){
        echo "Database error<br>";
        exit;
    }

    $username = input_clean($_SESSION['valid_user']);
    $query = 'select user_id, user_type from user where user_name=?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $user_type);
    $stmt->fetch();
    $stmt->close();
    
?>

<div class='row'>
    <div class='columns panel text-center large-11 large-centered medium-11 medium-centered small-10 small-centered'>
        <h3 style='color: #008cbb'>Bookmarks:<h3>

<?php
        $query = 'select d.dis_id, d.dis_name, d.dis_text 
                  from user u, bookmarked b, discussion d  
                  where u.user_id=? and u.user_id=b.user_id 
                  and b.dis_id=d.dis_id Order By b.date desc';

        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($b_dis_id, $b_dis_name, $b_dis_text);
        
        while($stmt->fetch()){
            echo "<div class='row'> ";
                echo "<input id='username' type='hidden' value='$username'>"; 
                echo "<input id='user_id' type='hidden' value='$user_id'>"; 
                echo "<input id='dis_id' type='hidden' value='$b_dis_id'>"; 
                echo " <hr>";
                echo " <div class='columns text-left large-8  medium-8 small-8'> ";
                    echo "<a href='discussion.php?dis_id=$b_dis_id'> <h5 style='color: #008cbb'> $b_dis_name </h5></a>";
                    echo "<h6> $b_dis_text </h6>";
                echo "</div> ";
                
                echo " <div class='columns text-right large-2 medium-2 small-4'> ";
                    echo "<a href='#' class='book_button'><h6 style='color:#e60000'>X</h6></a> ";  
                echo "</div> ";
            
            echo "</div> ";
        }

?>
    

    </div>
</div>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
      $(document).ready(function(){
        
            $('body').on('click','.book_button',function(e){
                e.preventDefault();
                var username = $(this).parent().parent().find('#username').val();
                var user_id  = $(this).parent().parent().find('#user_id').val();
                var dis_id = $(this).parent().parent().find('#dis_id').val();
                $(this).parent().parent().css("visibility", "hidden");
                $(this).parent().parent().css("display", "none");

                $.post('delete_bookmark.php', {username: username, user_id: user_id, dis_id: dis_id})
    



            });
    
       });
    </script>
  </body>
</html>
<?php
    exit;
}
else { 
?>
<div class='row'>
    <div class='large-7 columns panel large-centered text-center'>
        <h5>You must be logged in in order to manage your bookmarks!</h5>
        <h6>You can log in <a href='login.php'>here! </a></h6>
    </div>
</div>
<!-------------------------------------------------------------------->    
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
<?php
    exit;
}
?>
