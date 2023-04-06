<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Title</title>
</head>
<body>
    <nav class="main-nav">
        <h1>Library Management System</h1>
        <div>
            <a href="">log out</a>
        </div>
    </nav>
    <div class="side-bar">
        <ul>
            <li><a href="index.php?page=dashboard">Dashboard</a></li>
            <li><a href="index.php?page=books">Books</a></li>
            <li><a href="index.php?page=authors">Authors</a></li>
            <li><a href="index.php?page=categories">Categories</a></li>
        </ul>
    </div>
    <div class="content">
        <!-- here goes the dynamic content -->
        <?php
        if (isset($_GET["page"])) {
            if ($_GET["page"] == "books") {
                require_once "books.php";
            }
        }
        ?>
    </div>
</body>
</html>