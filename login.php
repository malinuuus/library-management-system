<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/login.css">
    <title>Login page</title>
</head>
<body>
    <div class="content">
        <div class="wrapper">
            <h1>Library Management System</h1>
            <p>Log in to access</p>
            <form action="scripts/authenticate.php" method="post">
                <input type="text" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
    <?php
    session_start();
    require_once "modal.php";
    ?>
    <script src="js/closeModal.js"></script>
</body>
</html>