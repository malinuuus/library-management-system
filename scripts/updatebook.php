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

    require_once "../classes/Database.php";
    $db = new Database("library_db");

    $result = $db->getResult(
        "SELECT COUNT(IF(is_available = 0, 1, NULL)) AS num_copies FROM books b LEFT JOIN copies c on b.id = c.book_id WHERE b.id = ?",
        array($_SESSION["updatingBookId"])
    );
    $borrowedCopiesCount = $result->fetch_assoc()["num_copies"];
    $copiesCount = $_POST["num_copies"];

    $result = $db->getResult("SELECT COUNT(*) AS count FROM books b INNER JOIN copies c on b.id = c.book_id WHERE b.id = ?", array($_SESSION["updatingBookId"]));
    $allCopiesCount = $result->fetch_assoc()["count"];

    if ($copiesCount < $borrowedCopiesCount) {
        $_SESSION["err"] = "Number of copies must be equal or larger than the number of borrowed copies!";
        echo "<script>history.back()</script>";
        exit();
    } else if ($copiesCount < $allCopiesCount) {
        // delete available copies
        $db->getResult("DELETE FROM copies WHERE is_available = 1 LIMIT ?", array($allCopiesCount - $copiesCount));

        if (!$db->checkAffectedRows($allCopiesCount - $copiesCount)) {
            $_SESSION["err"] = "Error occurred while deleting copies of the book!";
            echo "<script>history.back()</script>";
        }
    } else if ($copiesCount > $allCopiesCount) {
        // add copies
        for ($i = 0; $i < $copiesCount - $allCopiesCount; $i++) {
            $db->getResult("INSERT INTO copies (book_id, is_available) VALUES (?, 1)", array($_SESSION["updatingBookId"]));
        }

        if (!$db->checkAffectedRows($copiesCount - $allCopiesCount)) {
            $_SESSION["err"] = "Error occurred while adding copies of the book!";
            echo "<script>history.back()</script>";
        }
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