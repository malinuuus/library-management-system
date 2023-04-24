<?php
function uploadFile($bookId, $file) {
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

    $imgContent = addslashes(file_get_contents($tmpName));
    require_once "../classes/Database.php";
    $db = new Database("library_db");
    $db->getResult("UPDATE books SET image = ? WHERE id = ?", array($imgContent, $bookId));
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