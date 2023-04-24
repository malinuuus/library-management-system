<?php
function uploadFile($bookId, $file): bool {
    if (!file_exists($file["tmp_name"]) || !is_uploaded_file($file["tmp_name"])) {
        return true;
    }

    $fileName = $file["name"];
    $fileSize = $file["size"];
    $tmpName = $file["tmp_name"];
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);

    if ($fileSize > 2000000) {
        $_SESSION["err"] = "File size is too large!";
        return false;
    }

    $image = fopen($tmpName, 'rb');
    $imageData = fread($image, $fileSize);
    fclose($image);

    require_once "../classes/Database.php";
    $db = new Database("library_db");
    $db->getResult("UPDATE books SET image = ? WHERE id = ?", array($imageData, $bookId));

    if (!$db->checkAffectedRows(1)) {
        $db->close();
        $_SESSION["err"] = "Error occurred while uploading an image!";
        return false;
    }

    $db->close();
    return true;
}

function getFile($file, string $filePlaceholder = ""): string {
    if (isset($file)) {
        $filePath = "data:image/jpeg;base64,".base64_encode($file);
    } else {
        $filePath = $filePlaceholder;
    }

    return $filePath;
}