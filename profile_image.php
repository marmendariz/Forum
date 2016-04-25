<?php
$dir = "uploads/";
$uploadOk = 1;
$imageFileType = pathinfo($file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]['tmp_name']);

    if($check !== false) {

        echo "File is an image - " . $check["mime"] . ".";
        //check extensions
        $filename = strtolower($_FILES["fileToUpload"]['name']);
        $whitelist = array('jpg','png', 'gif', 'jpeg'); #example of white list
        $backlist = array('php', 'php3', 'php4', 'phtml','exe'); #example of black list

        if(!in_array(end(explode('.', $filename)), $whitelist))
        {
            echo 'Invalid file type';
            exit(0);
        }
        if(in_array(end(explode('.', $filename)), $backlist))
        {
            echo 'Invalid file type';
            exit(0);
        }

        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $message = "wrong answer";
        echo "<script type='text/javascript'>alert('$message');</script>"
            $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        $message = "wrong answer";
        echo "<script type='text/javascript'>alert('$message');</script>"
            // if everything is ok, try to upload file
    } 
    else{
        echo "Sorry, your file was not uploaded.";
        $message = "right answer";
        echo "<script type='text/javascript'>alert('$message');</script>"
            // if everything is ok, try to upload file
    } 

   /* else {
          $temp = explode(".", $filename);
          $newfilename = bob . '.' . end($temp);

        if (move_uploaded_file($_FILES["fileToUpload"]['tmp_name'], $dir.$newfilename)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

            /*$name = $_FILES["fileToUpload"]["name"];

            // change permissions... consider making script
            chmod("/home/stu/zgamino/public_html/sample/uploads/".$newfilename, 0744);

            $location= "img src='http://cs.csubak.edu/~zgamino/sample/uploads/".$newfilename."'";
            echo "<$location>";

}
     
    else {
        echo "Sorry, there was an error uploading your file.";
    }*/
//}
}
?>
