<?php
require_once "scripts/files.php";
require_once "classes/Database.php";
$db = new Database("library_db");

if (isset($_GET["id"])) {
    $result = $db->getResult("SELECT * FROM authors WHERE id = ?", array($_GET["id"]));

    // checking if author with giver in exists
    if ($result->num_rows === 0) {
        header("location: index.php?page=authors");
        exit();
    }

    $author = $result->fetch_assoc();
    $imagePath = getFilePath("images/authors/", $author["image_file_name"], "images/blank_author.jpg");

    echo <<< AUTHORINFO
        <h3>Authors &nbsp > &nbsp
            <span id="author-first-name">$author[first_name]</span>
            <span id="author-last-name">$author[last_name]</span>
        </h3>
        <img class="author-image" src=$imagePath alt="author photo">
        <p class="author-bio">$author[description]</p>
    AUTHORINFO;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $result = $db->getResult("SELECT is_admin FROM users WHERE id = ?", array($_SESSION["user_id"]));
    $user = $result->fetch_assoc();

    if ($user["is_admin"]) {
        echo <<< AUTHORINFO
            <div class="author-buttons">
                <form action="scripts/deleteauthor.php" method="post">
                    <input type="hidden" name="author_id" value="$author[id]">
                    <button type="submit">Delete author</button>
                </form>
                <form action="scripts/updateauthor.php" method="post" id="update-form" onsubmit="return false">
                    <input type="hidden" name="author_id" value="$author[id]">
                    <button type="button" id="update-author-btn">Edit author</button>
                </form>
            </div>
        AUTHORINFO;

        if (isset($_SESSION["err"])) {
            echo $_SESSION["err"];
            unset($_SESSION["err"]);
        }
    }

    echo "<h3>Books:</h3>";

    $result = $db->getResult("SELECT * FROM books b INNER JOIN authors a on b.author_id = a.id WHERE a.id = ?", array($_GET["id"]));

    while ($book = $result->fetch_assoc()) {
        $imagePath = "images/books/$book[cover_file_name]";

        if (!isset($book["cover_file_name"]) || !file_exists($imagePath)) {
            $imagePath = "images/blank_book.jpg";
        }

        echo <<< BOOK
            <div class="book-info">
                <img src=$imagePath alt="book cover" width="100">
                <p>$book[title]</p>
            </div>
        BOOK;
    }

    echo "<script src='js/updateAuthor.js'></script>";
} else {
    echo <<< AUTHORHEADER
        <h3>Authors</h3>
        <div class="search-bar">
            <label for="search-bar">🔍</label>
            <input type="text" id="search-bar" placeholder="Search...">
        </div>
    AUTHORHEADER;

    $result = $db->getResult("SELECT id, first_name, last_name, SUBSTR(description, 1, 300) AS description, image_file_name FROM authors ORDER BY last_name AND first_name");

    while ($author = $result->fetch_assoc()) {
        $imagePath = getFilePath("images/authors/", $author["image_file_name"], "images/blank_author.jpg");

        echo <<< AUTHOR
            <div class="author-info">
                <img src=$imagePath alt="author photo">
                <a href="index.php?page=authors&id=$author[id]">
                    <h4 class="author-name">$author[first_name] $author[last_name]</h4>
                    <p class="author-description">$author[description]...</p>
                </a>
            </div>
        AUTHOR;
    }

    echo "<script src='js/filterData.js'></script>";
}

$db->close();