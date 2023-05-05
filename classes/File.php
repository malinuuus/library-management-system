<?php

class File {
    private $file;
    private string $filePlaceholder;

    public function __construct($file, $filePlaceholder = "") {
        $this->file = $file;
        $this->filePlaceholder = $filePlaceholder;
    }

    public function upload_file($table, $id, &$message, $file = ""): bool {
        if (empty($file)) {
            $file = $this->file;
        }

        if (!file_exists($file["tmp_name"]) || !is_uploaded_file($file["tmp_name"])) {
            return true;
        }

        $fileName = $file["name"];
        $fileSize = $file["size"];
        $tmpName = $file["tmp_name"];
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
        $db->getResult("UPDATE $table SET image = ? WHERE id = ?", array($imageData, $id));

        if (!$db->checkAffectedRows(1)) {
            $message = "Error occurred while uploading an image!";
            return false;
        }

        $this->file = $imageData;
        return true;
    }

    public function get_file(): string
    {
        if (isset($this->file)) {
            $filePath = "data:image/jpeg;base64," . base64_encode($this->file);
        } else {
            $filePath = $this->filePlaceholder;
        }

        return $filePath;
    }
}