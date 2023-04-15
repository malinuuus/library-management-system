<?php
session_start();
require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult("DELETE FROM books WHERE id = ?", array($_POST["book_id"]));

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "The book has been deleted";
} else {
    $_SESSION["err"] = "Error occurred while deleting the book!";
}

header("location: ../index.php?page=books");

$db->close();