<?php
session_start();
require_once "../classes/Database.php";

// delete an image if exists
$db = new Database("library_db");
$result = $db->getResult("SELECT image_file_name FROM authors WHERE id = ?", array($_POST["author_id"]));
$fileName = $result->fetch_assoc()["image_file_name"];

require_once "files.php";
deleteFile("../images/authors/", $fileName);

$db->getResult("DELETE FROM authors WHERE id = ?", array($_POST["author_id"]));

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "The author has been deleted";
} else {
    $_SESSION["err"] = "Error occurred while deleting the author!";
}

header("location: ../index.php?page=authors");

$db->close();