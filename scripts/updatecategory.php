<?php
session_start();

foreach ($_POST as $key => $value) {
    if (empty($value)) {
        $_SESSION["err"] = "Category name can't be empty!";
        echo "<script>history.back();</script>";
        exit();
    }
}

require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult("UPDATE categories SET category = ? WHERE id = ?", [$_POST["category"], $_POST["category_id"]]);

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "The category has been successfully updated!";
} else {
    $_SESSION["err"] = "Error occurred while updating the category!";
}

header("location: ../index.php?page=categories");