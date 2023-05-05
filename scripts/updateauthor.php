<?php
session_start();

foreach ($_POST as $key => $value) {
    if (empty($value)) {
        $_SESSION["err"] = "Fill out all fields!";
        echo "<script>history.back();</script>";
        exit();
    }
}

require_once "../classes/Author.php";
$author = new Author($_POST["author_id"]);
$isUpdated = $author->update($_POST["first_name"], $_POST["last_name"], $_POST["description"]);

if ($isUpdated) {
    $_SESSION["err"] = "Author has been successfully updated";
} else {
    $_SESSION["err"] = "Author hasn't been updated!";
}

require_once "../classes/File.php";
$file = $author->image;

if (!$file->upload_file("authors", $_POST["author_id"], $message, $_FILES["image"])) {
    $_SESSION["err"] = $message;
    echo "<script>history.back();</script>";
    exit();
}

header("location: ../index.php?page=authors&id=$_POST[author_id]");