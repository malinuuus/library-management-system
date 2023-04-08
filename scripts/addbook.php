<?php
session_start();

foreach ($_POST as $key => $value) {
    if (empty($value)) {
        echo "<script>history.back();</script>";
        $_SESSION["err"] = "Fill out all fields!";
        exit();
    }
}

require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult(
    "INSERT INTO books (title, author_id, category_id) VALUES (?, ?, ?)",
    array($_POST["title"], $_POST["author_id"], $_POST["category_id"])
);

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "New book has been successfully added!";
} else {
    $_SESSION["err"] = "Error!";
}

header("location: ../books.php");