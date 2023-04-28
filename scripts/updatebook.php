<?php
session_start();

if (isset($_POST["book_id"])) {
    $_SESSION["updatingBookId"] = $_POST["book_id"];
    header("location: ../book.php");
} else if (isset($_SESSION["updatingBookId"])) {
    foreach ($_POST as $key => $value) {
        if (empty($value) && $key !== "num_copies") {
            $_SESSION["err"] = "Fill out all fields!";
            echo "<script>history.back();</script>";
            exit();
        }
    }

    require_once "../classes/Book.php";
    $isUpdated = (new Book($_SESSION["updatingBookId"]))->update($_POST["title"], $_POST["author_id"], $_POST["category_id"], $_POST["num_copies"], $message);

    if ($isUpdated) {
        $_SESSION["err"] = "The book has been successfully updated";
    } else {
        $_SESSION["err"] = $message;
        echo "<script>history.back();</script>";
        exit();
    }

    require_once "files.php";

    if (!uploadFile($_SESSION["updatingBookId"], $_FILES["image"])) {
        echo "<script>history.back();</script>";
        exit();
    }

    unset($_SESSION["updatingBookId"]);
    header("location: ../index.php?page=books");
}