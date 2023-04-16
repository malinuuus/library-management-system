<?php
require_once "delete.php";
deleteItem($_POST["book_id"], "book", "../images/books/", "../index.php?page=books");