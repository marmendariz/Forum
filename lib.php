<?php

function input_clean($input){
    return htmlspecialchars(strip_tags(trim($input)));
}


?>
