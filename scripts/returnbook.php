<?php
require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult(
    "UPDATE reservations SET return_date = NOW() WHERE id = ?",
    array($_POST["reservation_id"])
);

header("location: ../index.php?page=dashboard");