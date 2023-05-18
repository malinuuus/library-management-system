<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            echo "<script>history.back();</script>";
            $_SESSION["err"] = "Fill out all fields!";
            exit();
        }
    }

    require_once "../classes/Database.php";
    $db = new Database("library_db");
    $result = $db->getResult(
        "SELECT * FROM categories WHERE LOWER(category) = ?",
        [strtolower($_POST["category"])]
    );

    if ($result->num_rows > 0) {
        $_SESSION["err"] = "Provided category already exists!";
        echo "<script>history.back();</script>";
        exit();
    }

    $db->getResult("INSERT INTO categories (category) VALUES (?)", [$_POST["category"]]);

    if ($db->checkAffectedRows(1)) {
        $_SESSION["err"] = "New category has been successfully added!";
    } else {
        $_SESSION["err"] = "Error occurred while adding a new category!";
    }
}

header("location: ../index.php?page=categories");