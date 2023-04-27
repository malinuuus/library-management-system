<?php
session_start();
require_once "../classes/Author.php";
$isDeleted = (new Author($_POST["author_id"]))->delete();

if ($isDeleted) {
    $_SESSION["err"] = "The author has been deleted";
} else {
    $_SESSION["err"] = "Error occurred while deleting the author!";
}

header("location: ../index.php?page=authors");