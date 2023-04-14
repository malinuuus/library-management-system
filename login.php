<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login page</title>
</head>
<body>
    <div class="wrapper">
        <h1>Library Management System</h1>
        <p>Log in to access</p>
        <form action="scripts/authenticate.php" method="post">
            <input type="text" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" value="Login">
            <?php
            session_start();
            if (isset($_SESSION["err"])) {
                echo "<span>$_SESSION[err]</span>";
                unset($_SESSION["err"]);
            }
            ?>
        </form>
    </div>
</body>
</html>