<?php
function uploadFile($file, $destinationDir) {
    if (!file_exists($file["tmp_name"]) || !is_uploaded_file($file["tmp_name"])) {
        return null;
    }

    $fileName = $file["name"];
    $fileSize = $file["size"];
    $tmpName = $file["tmp_name"];
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);

    if ($fileSize > 2000000) {
        $_SESSION["err"] = "File size is too large!";
        return null;
    }

    $id = uniqid();
    $newFileName = "book$id.$ext";
    $newFilePath = $destinationDir.$newFileName;
    move_uploaded_file($tmpName, $newFilePath);

    return $newFileName;
}

function getFilePath(string $dir, $fileName, string $filePlaceholder = ""): string {
    $filePath = $dir.$fileName;

    if (!isset($fileName) || !file_exists($filePath)) {
        $filePath = $filePlaceholder;
    }

    return $filePath;
}

function deleteFile(string $dir, $fileName): bool {
    $filePath = $dir.$fileName;

    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    }
    return false;
}