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
    $newFileName = "book$id.$ext";
    $newFilePath = $destinationDir.$newFileName;
    move_uploaded_file($tmpName, $newFilePath);

    return $newFileName;
}

function getFilePath(string $dir, string $fileName, string $filePlaceholder = ""): string {
    $imagePath = $dir.$fileName;

    if (!isset($fileName) || !file_exists($imagePath)) {
        $imagePath = $filePlaceholder;
    }

    return $imagePath;
}