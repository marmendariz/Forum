<?
include_once 'lib.php';
set_path();
session_start();

function print_comment($db,$logged_in, $dis_id, $com_id, $com_name, 
    $com_level, $com_text, $com_flag, $parent_com_id, 
    $upvote_count, $downvote_count, $username, $pimg){

        echo "<div class='row comment'>";//1
            if($com_level == 2){
                $level2 = true;
            echo "<div class='columns large-10 medium-10 small-10 panel right'>";//2
            }
            else
            echo "<div class='columns large-10 medium-12 small-12 panel small-centered'>";//2

                echo "<div class='row'>";//3
                /*****/
                    echo "<div class='columns large-2 medium-2 small-3 small-centered large-uncentered medium-uncentered text-center'>";//4
                        echo "<div class='row'>";//5
                            echo "<div class='large-12 medium-12 small-12 columns text-center large-uncentered medium-uncentered small-centered'>";//6
                                echo "<h6 class='text-center'><b>".stripslashes($username)."</b></h6>";
                            echo "</div>";//6
                        echo "</div>";//5
                        echo "<div class='row'>";//7
                            echo "<div class='large-12 medium-12 show-for-medium-up columns'>";//8
                                echo "<img class='user_comment_info' src='$pimg'>";
                            echo "</div>";//8
                        echo "</div>";//7
                    echo "</div>";//4
                /*****/

                    echo "<div class='columns large-9 medium-8 small-9'>";//9
                        echo "<input class='com_level' id='$com_id' type='hidden' value='$com_level'>"; /*COM LEVEL*/
                        echo "<input class='parent_com_id' type='hidden' value='$parent_com_id'>";
                        echo "<input class='com_id' type='hidden' value='$com_id'>";
                        echo "<hr>";
                        echo "<p class='comment_text'>".stripslashes($com_text)."</p>";
                        echo "<hr>";
                        
                        /******* Comment links  *********/
                        echo "<div class='row com_links text-center'>";//10
                            echo "<div class='columns large-4 medium-4 small-4'>";//11
                                if($logged_in)
                                echo "<h6><a class='comment_reply_link'>Reply</a></h6>";
                                else
                                echo "<h6><a href='login.php' class='login_reply_link'>Login to Reply</a></h6>";
                            echo "</div>";//11

                            if($username === $_SESSION['valid_user']){
                            echo "<div class='columns large-4 medium-4 small-4'>";//12
                                echo "<h6><a class='comment_edit_link'>Edit</a></h6>";
                            echo "</div>";//12
                            echo "<div class='columns large-4 medium-4 small-4'>";//13
                                echo "<h6><a class='comment_delete_link'>Delete</a></h6>";
                            echo "</div>";//13
                            }
                        echo "</div>";//10
                    echo "</div>";//9
        /****** End Comment Links  *****/

        /************* UP/DOWN VOTE SECTION *******************/ 
                    echo "<div class='large-1 medium-2 small-3 columns text-center'>";//14
                        echo "<div class='row'>";//15
                            echo "<div class='large-12 medium-8 small-12 columns small-centered'>";//16
                                echo "<a class='up_vote'><img src='img/up.png'></a>";
                            echo "</div>";//16
                        echo "</div>";//15

                        echo "<div class='row'>";//17
                            echo "<div class='large-12 medium-8 small-12 columns small-centered'>";//18
                                echo "<h6 class='vote_count'>".($upvote_count-$downvote_count)."</h6>";
                            echo "</div>";//18
                        echo "</div>";//17

                        echo "<div class='row'>";//19
                            echo "<div class='large-12 medium-8 small-12 columns small-centered'>";//20
                                echo "<a class='down_vote'><img src='img/down.png'></a>";
                            echo "</div>";//20
                        echo "</div>";//19
                    echo "</div>";//14
                        
                        
                        
        echo "</div>";//3
        echo "</div>";//2
        echo "</div>";//1
    }

class comment{
    var $dis_id, $com_id, $com_name,
        $com_level, $com_text, $com_flag,
        $parent_com_id, $upvote_count, $downvote_count,
        $username, $pimg;
}


