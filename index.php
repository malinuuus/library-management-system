<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php
        echo isset($_GET["page"]) ? ucfirst($_GET["page"]) : "Dashboard";
        ?>
    </title>
</head>
<body>
    <nav class="main-nav">
        <h1>Library Management System</h1>
        <div>
            <a href="index.php?page=userProfile&id=<?php echo $_SESSION["user_id"] ?>">
                <?php
                if (isset($_SESSION["user_id"])) {
                    require_once "classes/Database.php";
                    $db = new Database("library_db");
                    $result = $db->getResult("SELECT first_name, last_name FROM users WHERE id = ?", array($_SESSION["user_id"]));
                    $user = $result->fetch_assoc();
                    $db->close();

                    echo "$user[first_name] $user[last_name]";
                }
                ?>
            </a>
            <a href="scripts/logout.php">log out</a>
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
            } else if ($_GET["page"] == "authors") {
                require_once "authors.php";
            } else if ($_GET["page"] == "dashboard") {
                require_once "dashboard.php";
            } else if ($_GET["page"] == "userProfile") {
                require_once "userprofile.php";
            }
        } else {
            require_once "dashboard.php";
        }
        ?>
    </div>
</body>
</html>