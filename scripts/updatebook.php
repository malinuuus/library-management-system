<?php
session_start();

if (isset($_POST["book_id"])) {
    $_SESSION["updatingBookId"] = $_POST["book_id"];
    header("location: ../book.php");
} else if (isset($_SESSION["updatingBookId"])) {
    foreach ($_POST as $key => $value) {
        // todo: correct 0 value for number of copies
        if (empty($value) && (int)$value !== 0) {
            echo "<script>history.back();</script>";
            $_SESSION["err"] = "Fill out all fields!";
            exit();
        }
    }

    require_once "../classes/Database.php";
    $db = new Database("library_db");
    $db->getResult(
        "UPDATE books SET title = ?, author_id = ?, category_id = ? WHERE id = ?",
        array($_POST["title"], $_POST["author_id"], $_POST["category_id"], $_SESSION["updatingBookId"])
    );

    if ($db->checkAffectedRows(1)) {
        $_SESSION["err"] = "The book has been successfully updated";
    } else {
        $_SESSION["err"] = "Error occurred while updating the book!";
    }

    unset($_SESSION["updatingBookId"]);
    $db->close();
    header("location: ../index.php?page=books");
}