<?php
$uploadFileMessage='';

function uploadImage(&$imageDir, &$imageFile) {
    global $uploadFileMessage;

    $imageFile = basename($_FILES["fileToUpload"]["name"]);

    if(!isset($_FILES["fileToUpload"]) || empty($imageFile)) {
        $uploadFileMessage = "No image chosen to upload.";
        return false;
    }

    $target_file = $imageDir . $imageFile;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadFileMessage = "File is an image - " . $check["mime"] . ".";
    } else {
        $uploadFileMessage = "File is not an image.";
        return false;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadFileMessage = "Image already exists.";
        return false;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $uploadFileMessage = "Image is too large.";
        return false;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $uploadFileMessage = "Only JPG, JPEG, PNG & GIF files are allowed.";
        return false;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $uploadFileMessage = "Image was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $uploadFileMessage = "The image ". $imageFile . " has been uploaded successfully.";
            return true;
        } else {
            $uploadFileMessage = "Error uploading image.";
            return false;
        }
    }

    return false;
}

function deleteImage(&$imageDir, &$imageFile) {
    global $uploadFileMessage;

    $target_file = $imageDir . $imageFile;

    // Check if file exists
    if (file_exists($target_file)) {
        unlink($target_file);
        $uploadFileMessage = "File $imageFile has been deleted.";
        return true;
    } else {
        $uploadFileMessage = "File $imageFile doesn't exist.";
        return false;
    }
}

?>