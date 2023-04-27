<?php
session_start();

if (empty($_POST["email"]) || empty($_POST["password"])) {
    $_SESSION["err"] = "Fill email and password inputs!";
    header("location: ../login.php");
    exit();
}

require_once "../classes/Database.php";
$db = new Database("library_db");
$result = $db->getResult("SELECT id FROM users WHERE email = ?", array($_POST["email"]));
$userId = $result->fetch_assoc()["id"];

if (isset($userId)) {
    require_once "../classes/User.php";
    $user = new User($userId);

    if ($user->login($_POST["password"])) {
        // successfully logged in
        $_SESSION["user_id"] = $user->id;
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
