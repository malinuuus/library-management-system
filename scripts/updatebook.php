<?php
session_start();

if (isset($_POST["book_id"])) {
    $_SESSION["updatingBookId"] = $_POST["book_id"];
    header("location: ../book.php");
} else if (isset($_SESSION["updatingBookId"])) {
    foreach ($_POST as $key => $value) {
        // todo: correct 0 value for number of copies
        if (empty($value) && (int)$value !== 0) {
            $_SESSION["err"] = "Fill out all fields!";
            echo "<script>history.back();</script>";
            exit();
        }
    }

    require_once "../classes/Database.php";
    $db = new Database("library_db");

    $result = $db->getResult(
        "SELECT COUNT(IF(is_available = 1, 1, NULL)) AS num_copies FROM books b LEFT JOIN copies c on b.id = c.book_id WHERE b.id = ?",
        array($_SESSION["updatingBookId"])
    );
    $availableCopiesCount = $result->fetch_assoc()["num_copies"];

    if ($_POST["num_copies"] < $availableCopiesCount) {
        $_SESSION["err"] = "Number of copies must be equal or larger than the number of available copies!";
        echo "<script>history.back()</script>";
        exit();
    }

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