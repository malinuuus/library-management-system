<?php
session_start();
require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult("DELETE FROM categories WHERE id = ?", [$_POST["category_id"]]);

// todo: check if a book with this category exists
if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "The category has been successfully deleted!";
} else {
    $_SESSION["err"] = "Error occurred while deleting the category!";
}

header("location: ../index.php?page=categories");