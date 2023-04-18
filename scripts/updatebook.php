<?php
session_start();
$_SESSION["updatingBookId"] = $_POST["book_id"];
header("location: ../book.php");