<?php
require_once "scripts/files.php";
require_once "classes/Database.php";
$db = new Database("library_db");

if (isset($_GET["id"])) {
    $result = $db->getResult("SELECT * FROM authors WHERE id = ?", array($_GET["id"]));
    $author = $result->fetch_assoc();
    $imagePath = getFilePath("images/authors/", $author["image_file_name"], "images/blank_author.jpg");

    echo <<< AUTHORINFO
        <h3>Authors &nbsp > &nbsp $author[first_name] $author[last_name]</h3>
        <div class="author-info">
            <img src=$imagePath alt="author photo" width="100">
            <p>$author[description]</p>
        </div>
        <h3>Books:</h3>
    AUTHORINFO;

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
} else {
    echo <<< AUTHORHEADER
        <h3>Authors</h3>
        <div>
            <label class="search-bar-label" for="search-bar">🔍</label>
            <input type="text" id="search-bar">
        </div>
    AUTHORHEADER;

    $result = $db->getResult("SELECT id, first_name, last_name, SUBSTR(description, 1, 300) AS description, image_file_name FROM authors ORDER BY last_name AND first_name");

    while ($author = $result->fetch_assoc()) {
        $imagePath = getFilePath("images/authors/", $author["image_file_name"], "images/blank_author.jpg");

        echo <<< AUTHOR
            <div class="author-info">
                <img src=$imagePath alt="author photo" width="100">
                <a href="index.php?page=authors&id=$author[id]">
                    <h4 class="author-name">$author[first_name] $author[last_name]</h4>
                    <p class="author-description">$author[description]...</p>
                </a>
            </div>
        AUTHOR;
    }

    echo "<script src='js/filter_data.js'></script>";
}

$db->close();