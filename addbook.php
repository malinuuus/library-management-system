<?php
session_start();

// if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit();
}

require_once "classes/Database.php";
$db = new Database("library_db");
$result = $db->getResult("SELECT is_admin FROM users WHERE id = ?", array($_SESSION["user_id"]));
$user = $result->fetch_assoc();

// if user doesn't have admin rights
if (!$user["is_admin"]) {
    header("location: index.php?page=books");
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add a book</title>
</head>
<body>
    <div class="wrapper">
        <h3>Add a new book to database</h3>
        <form action="scripts/addbook.php" method="post">
            <input type="text" name="title" placeholder="Title">
            <label>
                Author
                <select name="author_id">
                    <?php
                    $result = $db->getResult("SELECT id, first_name, last_name FROM authors");

                    while ($author = $result->fetch_assoc()) {
                        echo "<option value='$author[id]'>$author[first_name] $author[last_name]</option>";
                    }
                    ?>
                </select>
            </label>
            <label>
                Category
                <select name="category_id">
                    <?php
                    $result = $db->getResult("SELECT * FROM categories");

                    while ($category = $result->fetch_assoc()) {
                        echo "<option value='$category[id]'>$category[category]</option>";
                    }

                    $db->close();
                    ?>
                </select>
            </label>
            <button type="submit">Add</button>
            <?php
            if (isset($_SESSION["err"])) {
                echo "<span>$_SESSION[err]</span>";
                unset($_SESSION["err"]);
            }
            ?>
        </form>
    </div>
</body>
</html>