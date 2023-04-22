<?php
session_start();
$error = false;

foreach ($_POST as $key => $value) {
    if (empty($value) && $key !== "new_password1" && $key !== "new_password2") {
        $_SESSION["err"] = "Fill out all fields!";
        $error = true;
        break;
    }
}

if ($_POST["new_password1"] !== $_POST["new_password2"]) {
    $_SESSION["err"] = "Passwords are different!";
    $error = true;
}

if ($error) {
    echo "<script>history.back();</script>";
    exit();
}

$hashedPassword = password_hash($_POST["new_password1"], PASSWORD_DEFAULT);
$isAdmin = $_POST["admin_rights"] == "on" ? 1 : 0;

require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult(
    "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ?, is_admin = ? WHERE id = ?",
    array($_POST["first_name"], $_POST["last_name"], $_POST["email"], $hashedPassword, $isAdmin, $_POST["user_id"])
);

if ($db->checkAffectedRows(1)) {
    $_SESSION["err"] = "User has been successfully updated";
} else {
    $_SESSION["err"] = "User hasn't been updated!";
}

$db->close();
header("location: ../index.php?page=userProfile&id=$_POST[user_id]");