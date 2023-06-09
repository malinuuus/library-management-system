<?php
// page to update and add a book to the database
session_start();

if (isset($_SESSION["updatingBookId"]) && isset($_GET["mode"]) && $_GET["mode"] === "add") {
    unset($_SESSION["updatingBookId"]);
}

// if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit();
}

require_once "classes/User.php";
$user = new User($_SESSION["user_id"]);

// if user doesn't have admin rights
if (!$user->isAdmin) {
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
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/addform.css">
    <title>Add a book</title>
</head>
<body>
    <div class="wrapper">
        <?php
        require_once "classes/Database.php";
        $db = new Database("library_db");

        $updatedTitle = "";
        $updatedAuthorId = 0;
        $updatedCategoryId = 0;
        $updatedNumOfCopies = "";
        $actionPath = "scripts/addbook.php";
        $pageTitle = "Add a new book to database";

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
            $actionPath = "scripts/updatebook.php";
            $pageTitle = "Update a selected book";
        }

        echo "<h3>$pageTitle</h3>";
        echo "<form action='$actionPath' method='post' enctype='multipart/form-data'>";
        echo "<div><input type='text' name='title' placeholder='Title' value='$updatedTitle'></div>";
        ?>
        <div>
            <label for="author">Author</label>
            <select name="author_id" id="author">
                <?php
                require_once "classes/Author.php";
                $result = $db->getResult("SELECT id FROM authors");

                while ($authorResult = $result->fetch_assoc()) {
                    $author = new Author($authorResult["id"]);

                    if ($author->id === $updatedAuthorId) {
                        echo "<option selected value='$author->id'>$author->firstName $author->lastName</option>";
                    } else {
                        echo "<option value='$author->id'>$author->firstName $author->lastName</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div>
            <label for="category">Category</label>
            <select name="category_id" id="category">
                <?php
                $result = $db->getResult("SELECT * FROM categories");

                while ($category = $result->fetch_assoc()) {
                    if ($category["id"] === $updatedCategoryId) {
                        echo "<option selected value='$category[id]'>$category[category]</option>";
                    } else {
                        echo "<option value='$category[id]'>$category[category]</option>";
                    }
                }
                ?>
            </select>
        </div>
        <?php
        echo "<div><input type='number' name='num_copies' placeholder='Number of copies' value='$updatedNumOfCopies'></div>";
        ?>
        <div>
            <label for="image">Image: </label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>
        <?php
        if (isset($_SESSION["updatingBookId"])) {
            echo "<button type='submit'>Update</button>";
        } else {
            echo "<button type='submit'>Add</button>";
        }
        ?>
        <a href="index.php?page=books">Cancel</a>
        </form>
    </div>
    <?php
    require_once "notificationmodal.php";
    ?>
    <script src="js/closeModal.js"></script>
</body>
</html>