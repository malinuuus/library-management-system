<?php
session_start();
require_once "../classes/Database.php";

// delete an image if exists
$db = new Database("library_db");
$result = $db->getResult("SELECT cover_file_name FROM books WHERE id = ?", array($_POST["book_id"]));
$fileName = $result->fetch_assoc()["cover_file_name"];

require_once "files.php";
deleteFile("../images/books/", $fileName);

$db->getResult("DELETE FROM books WHERE id = ?", array($_POST["book_id"]));

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "The book has been deleted";
} else {
    $_SESSION["err"] = "Error occurred while deleting the book!";
}

header("location: ../index.php?page=books");

$db->close();