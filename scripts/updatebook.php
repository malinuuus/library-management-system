<?php
session_start();

foreach ($_POST as $key => $value) {
    // todo: correct 0 value for number of copies
    if (empty($value) && (int)$value !== 0) {
        echo "<script>history.back();</script>";
        $_SESSION["err"] = "Fill out all fields!";
        exit();
    }
}

print_r($_POST);

require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult(
    "UPDATE books SET title = ?, author_id = ?, category_id = ? WHERE id = ?",
    array($_POST["title"], $_POST["author_id"], $_POST["category_id"], $_POST["book_id"])
);

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "The book has been successfully updated";
} else {
    $_SESSION["err"] = "Error occurred while deleting the book!";
}

header("location: ../index.php?page=books");