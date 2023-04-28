<?php

class File {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function upload_file($bookId, &$message): bool {
        if (!file_exists($this->file["tmp_name"]) || !is_uploaded_file($this->file["tmp_name"])) {
            return true;
        }

        $fileName = $this->file["name"];
        $fileSize = $this->file["size"];
        $tmpName = $this->file["tmp_name"];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileSize > 2000000) {
            $message = "File is too large!";
            return false;
        }

        $image = fopen($tmpName, 'rb');
        $imageData = fread($image, $fileSize);
        fclose($image);

        require_once "../classes/Database.php";
        $db = new Database("library_db");
        $db->getResult("UPDATE books SET image = ? WHERE id = ?", array($imageData, $bookId));

        if (!$db->checkAffectedRows(1)) {
            $message = "Error occurred while uploading an image!";
            return false;
        }

        return true;
    }

    public function get_file(string $filePlaceholder = ""): string
    {
        if (isset($this->file)) {
            $filePath = "data:image/jpeg;base64," . base64_encode($this->file);
        } else {
            $filePath = $filePlaceholder;
        }

        return $filePath;
    }
}