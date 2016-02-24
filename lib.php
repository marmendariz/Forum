<?php

function input_clean($input){
    return mysqli_real_escape_string(htmlspecialchars(strip_tags(trim($input))));
}


?>
