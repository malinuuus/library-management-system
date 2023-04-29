<?php
session_start();
require_once "../classes/Book.php";
$isDeleted = (new Book($_POST["book_id"]))->delete();

if ($isDeleted) {
    $_SESSION["err"] = "The book has been deleted";
} else {
    $_SESSION["err"] = "Error occurred while deleting the book!";
}

header("location: ../index.php?page=books");