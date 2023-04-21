<?php
session_start();

foreach ($_POST as $key => $value) {
    if (empty($value) && $key !== "num_copies") {
        echo "<script>history.back();</script>";
        $_SESSION["err"] = "Fill out all fields!";
        exit();
    }
}

require_once "files.php";
$fileName = uploadFile($_FILES["image"], "../images/books/");

if (isset($_SESSION["err"])) {
    echo "<script>history.back();</script>";
    exit();
}

require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult(
    "INSERT INTO books (title, author_id, category_id, cover_file_name) VALUES (?, ?, ?, ?);",
    array($_POST["title"], $_POST["author_id"], $_POST["category_id"], $fileName)
);

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "New book has been successfully added!";
} else {
    $_SESSION["err"] = "Error!";
}

// adding copies
$result = $db->getResult("SELECT MAX(id) AS book_id FROM books;");
$book_id = $result->fetch_assoc()["book_id"];

$num_copies = (int)$_POST["num_copies"];

for ($i = 0; $i < $num_copies; $i++) {
    $db->getResult("INSERT INTO copies (book_id, is_available) VALUES (?, 1)", array($book_id));
}

$db->close();
header("location: ../index.php?page=books");