<?php
session_start();

foreach ($_POST as $key => $value) {
    if (empty($value)) {
        $_SESSION["err"] = "Fill out all fields!";
        echo "<script>history.back();</script>";
        exit();
    }
}

require_once "../classes/Author.php";
$isUpdated = (new Author($_POST["author_id"]))->update($_POST["first_name"], $_POST["last_name"], $_POST["description"]);

if ($isUpdated) {
    $_SESSION["err"] = "Author has been successfully updated";
} else {
    $_SESSION["err"] = "Author hasn't been updated!";
}

header("location: ../index.php?page=authors&id=$_POST[author_id]");