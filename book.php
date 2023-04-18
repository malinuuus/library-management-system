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
<?php
if (isset($_SESSION["updatingBookId"])) {
    echo $_SESSION["updatingBookId"];
}
?>
    <div class="wrapper">
        <h3>Add a new book to database</h3>
        <form action="scripts/addbook.php" method="post" enctype="multipart/form-data">
            <?php
            $updatedTitle = "";
            $updatedAuthorId = 0;
            $updatedCategoryId = 0;
            $updatedNumOfCopies = 0;

            if (isset($_SESSION["updatingBookId"])) {
                $result = $db->getResult(
                        "SELECT b.title, a.id AS a_id, c.id AS c_id, COUNT(c2.id) AS copies FROM books b INNER JOIN authors a on b.author_id = a.id INNER JOIN categories c on b.category_id = c.id INNER JOIN copies c2 on b.id = c2.book_id WHERE b.id = ?",
                    array($_SESSION["updatingBookId"])
                );
                $book = $result->fetch_assoc();
                $updatedTitle = $book["title"];
                $updatedAuthorId = $book["a_id"];
                $updatedCategoryId = $book["c_id"];
                $updatedNumOfCopies = $book["copies"];
            }

            echo "<input type='text' name='title' placeholder='Title' value='$updatedTitle'>";
            ?>
            <label>
                Author
                <select name="author_id">
                    <?php
                    $result = $db->getResult("SELECT id, first_name, last_name FROM authors");

                    while ($author = $result->fetch_assoc()) {
                        if ($author["id"] === $updatedAuthorId) {
                            echo "<option selected value='$author[id]'>$author[first_name] $author[last_name]</option>";
                        } else {
                            echo "<option value='$author[id]'>$author[first_name] $author[last_name]</option>";
                        }
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
                        if ($category["id"] === $updatedCategoryId) {
                            echo "<option selected value='$category[id]'>$category[category]</option>";
                        } else {
                            echo "<option value='$category[id]'>$category[category]</option>";
                        }
                    }

                    $db->close();
                    ?>
                </select>
            </label>
            <?php
            echo "<input type='number' name='num_copies' placeholder='Number of copies' value='$updatedNumOfCopies'>";
            ?>
            <label for="image">Image: </label>
            <input type="file" name="image" id="image" accept="image/*">
            <?php
            if (isset($_SESSION["updatingBookId"])) {
                echo "<button type='submit'>Update</button>";
                unset($_SESSION["updatingBookId"]);
            } else {
                echo "<button type='submit'>Add</button>";
            }

            if (isset($_SESSION["err"])) {
                echo "<span>$_SESSION[err]</span>";
                unset($_SESSION["err"]);
            }
            ?>
        </form>
    </div>
</body>
</html>