<?php
function uploadFile($file, $destinationDir) {
    $fileName = $file["name"];
    $fileSize = $file["size"];
    $tmpName = $file["tmp_name"];
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);

    if ($fileSize > 1000000) {
        $_SESSION["err"] = "File size is too large!";
        exit();
    }

    $id = uniqid();
    $newImageName = "book$id.$ext";
    $newImagePath = $destinationDir.$newImageName;
    move_uploaded_file($tmpName, $newImagePath);

    return $newImageName;
}