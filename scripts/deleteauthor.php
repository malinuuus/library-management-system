<?php
require_once "delete.php";
deleteItem($_POST["author_id"], "author", "../images/authors/", "../index.php?page=authors");