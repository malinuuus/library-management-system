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
$db->getResult(
    "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?",
    array($_POST["first_name"], $_POST["last_name"], $_POST["email"], $_POST["user_id"])
);

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "User has been successfully updated";
} else {
    $_SESSION["err"] = "User hasn't been updated!";
}

$db->close();
header("location: ../index.php?page=userProfile&id=$_POST[user_id]");