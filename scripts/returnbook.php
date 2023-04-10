<?php
$now = new DateTime();

require_once "../classes/Database.php";
$db = new Database("library_db");
$db->getResult(
    "UPDATE reservations SET return_date = ? WHERE id = ?",
    array($now->format("Y-m-d H:i:s"), $_POST["reservation_id"])
);

$db->close();
header("location: ../index.php?page=dashboard");