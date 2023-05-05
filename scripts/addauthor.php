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
    "INSERT INTO authors (first_name, last_name, description) VALUES (?, ?, ?)",
    array($_POST["first_name"], $_POST["last_name"], $_POST["description"])
);

$result = $db->getResult("SELECT MAX(id) AS author_id FROM authors");
$authorId = $result->fetch_assoc()["author_id"];

require_once "../classes/File.php";
$file = new File($_FILES["image"]);

if (!$file->upload_file("authors", $authorId, $message)) {
    $_SESSION["err"] = $message;
    echo "<script>history.back();</script>";
    exit();
}

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "New author has been successfully added!";
} else {
    $_SESSION["err"] = "Error!";
}

header("location: ../index.php?page=authors");