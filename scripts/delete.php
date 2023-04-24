<?php
session_start();
require_once "../classes/Database.php";

function deleteItem($itemType, $redirectSubPage) {
    $db = new Database("library_db");

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