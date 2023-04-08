<?php
require_once "classes/Database.php";
$db = new Database("library_db");

if (isset($_GET["id"])) {
    $result = $db->getResult("SELECT * FROM authors WHERE id = ?", array($_GET["id"]));
    $author = $result->fetch_assoc();

    echo <<< AUTHORINFO
        <h3>Authors &nbsp > &nbsp $author[first_name] $author[last_name]</h3>
        <p>$author[description]</p>
        <h3>Books:</h3>
    AUTHORINFO;

    $result = $db->getResult("SELECT * FROM books b INNER JOIN authors a on b.author_id = a.id WHERE a.id = ?", array($_GET["id"]));

    while ($book = $result->fetch_assoc()) {
        echo "<p>$book[title]</p>";
    }
} else {
    echo "<h3>Authors</h3>";
    $result = $db->getResult("SELECT id, first_name, last_name, SUBSTR(description, 1, 300) as description FROM authors ORDER BY last_name AND first_name");

    while ($author = $result->fetch_assoc()) {
        echo <<< AUTHOR
            <div class="author-info">
                <a href="index.php?page=authors&id=$author[id]">
                    <h4>$author[first_name] $author[last_name]</h4>
                    <p>$author[description]...</p>
                </a>
            </div>
        AUTHOR;
    }
}

$db->close();