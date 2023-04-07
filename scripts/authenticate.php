<?php

session_start();
$email = $_POST["email"];
$password = $_POST["password"];

if (empty($email) || empty($password)) {
    $_SESSION["err"] = "Fill email and password inputs!";
    header("location: ../login.php");
    exit();
}

require_once "../classes/Database.php";
$db = new Database("library_db");
$result = $db->getResult("SELECT id, password FROM users WHERE email = ?", array($email));

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $hashedPassword = $user["password"];

    if (password_verify($password, $hashedPassword)) {
        // successfully logged in
        $_SESSION["user_id"] = $user["id"];
        unset($_SESSION["err"]);
        header("location: ../index.php");
    } else {
        $_SESSION["err"] = "Wrong password!";
        header("location: ../login.php");
    }
} else {
    $_SESSION["err"] = "User was not found!";
    header("location: ../login.php");
}

$db->close();