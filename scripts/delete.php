<?php
session_start();
require_once "../classes/Database.php";

function deleteItem($id, $itemType, $imageDir, $redirectSubPage) {
    $db = new Database("library_db");

    if ($itemType === "author") {
        $result = $db->getResult("SELECT image_file_name FROM authors WHERE id = ?", array($id));
        $fileName = $result->fetch_assoc()["image_file_name"];
    } else if ($itemType === "book") {
        $result = $db->getResult("SELECT cover_file_name FROM books WHERE id = ?", array($id));
        $fileName = $result->fetch_assoc()["cover_file_name"];
    } else {
        $_SESSION["err"] = "Invalid item type";
        return;
    }

    require_once "files.php";
    deleteFile($imageDir, $fileName);

    if ($itemType === "author") {
        $db->getResult("DELETE FROM authors WHERE id = ?", array($_POST["author_id"]));
    } else if ($itemType === "book") {
        $db->getResult("DELETE FROM books WHERE id = ?", array($_POST["book_id"]));
    }

    if ($db->checkAffectedRows(1)) {
        $_SESSION["err"] = "The $itemType has been deleted";
    } else {
        $_SESSION["err"] = "Error occurred while deleting the $itemType!";
    }

    header("location: $redirectSubPage");
    $db->close();
}