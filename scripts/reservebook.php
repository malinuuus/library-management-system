<?php
$book_id = $_POST["book_id"];

session_start();
$user_id = $_SESSION["user_id"];

require_once "../classes/Database.php";
$db = new Database("library_db");
$result = $db->getResult("SELECT id FROM copies WHERE book_id = ? AND is_available = 1 LIMIT 1", array($book_id));
$copy_id = $result->fetch_assoc()["id"];

$now = new DateTime();
$due_date = new DateTime("+1 month");

$db->getResult(
    "INSERT INTO reservations (copy_id, user_id, reservation_date, due_date) VALUES (?, ?, ?, ?);",
    array($copy_id, $user_id, $now->format("Y-m-d H:i:s"), $due_date->format("Y-m-d"))
);

if ($db->checkAffectedRows(1)) {
    $db->getResult("UPDATE copies SET is_available = 0 WHERE id = ?", array($copy_id));
    $_SESSION["err"] = "The book has been successfully borrowed";
} else {
    $_SESSION["err"] = "Error with borrowing the book";
}

$db->close();
header("location: ../index.php?page=books");