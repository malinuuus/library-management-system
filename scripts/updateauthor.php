<?php
session_start();

foreach ($_POST as $key => $value) {
    if (empty($value)) {
        $_SESSION["err"] = "Fill out all fields!";
        echo "<script>history.back();</script>";
        exit();
    }
}

require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult("UPDATE authors SET first_name = ?, last_name = ?, description = ? WHERE id = ?", array($_POST["first_name"], $_POST["last_name"], $_POST["description"], $_POST["author_id"]));

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "Author has been successfully updated";
} else {
    $_SESSION["err"] = "Error occurred while updating the author!";
}

$db->close();
header("location: ../index.php?page=authors&id=$_POST[author_id]");