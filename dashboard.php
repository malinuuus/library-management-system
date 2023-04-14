<h3>Dashboard</h3>
<h3>Notifications:</h3>
<?php
require_once "classes/Database.php";
$db = new Database("library_db");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$result = $db->getResult("SELECT is_admin FROM users WHERE id = ?", array($_SESSION["user_id"]));
$user = $result->fetch_assoc();

if ($user["is_admin"]) {
    $query = "SELECT CONCAT(u.first_name, ' ' ,u.last_name) AS user, DATE_FORMAT(r.reservation_date, '%Y-%m-%d') AS reservation_date, DATE_FORMAT(r.due_date, '%Y-%m-%d') AS due_date, b.title, CONCAT(a.first_name, ' ', a.last_name) AS author, r.id, c.id AS copy_id, r.due_date < NOW() AS is_after_duedate FROM reservations r
              INNER JOIN users u on r.user_id = u.id
              INNER JOIN copies c on r.copy_id = c.id
              INNER JOIN books b on c.book_id = b.id
              INNER JOIN authors a on b.author_id = a.id
              WHERE r.return_date IS NULL
              ORDER BY r.reservation_date DESC";

    $result = $db->getResult($query);

    while ($res = $result->fetch_assoc()) {
        echo "<div class='notification'>";
        echo $res["is_after_duedate"] ? "<p>$res[user] hasn't returned the book yet!</p>" : "<p>$res[user] borrowed a book on $res[reservation_date]</p>";

        echo <<< NOTIFICATION
                <div class="notification-content">
                    <div class="notification-info">
                        <p>$res[title]</p>
                        <p>by $res[author]</p>
                        <p>id of copy: $res[copy_id]</p>
        NOTIFICATION;

        echo $res["is_after_duedate"] ? "<p>due date was: $res[due_date]</p>" : "<p>due date is: $res[due_date]</p>";

        echo <<< NOTIFICATION
                    </div>
                    <form action="scripts/returnbook.php" method="post">
                        <input type="hidden" name="reservation_id" value="$res[id]">
                        <button type="submit">Book returned</button>
                    </form>
                </div>
            </div>
        NOTIFICATION;
    }
} else {
    // todo: get user_id from session var, not from the database
    // todo: add notifications about reservations' due date
    $query = "SELECT DATE_FORMAT(r.reservation_date, '%Y-%m-%d') AS reservation_date, DATE_FORMAT(r.due_date, '%Y-%m-%d') AS due_date, b.title, CONCAT(a.first_name, ' ', a.last_name) AS author, r.due_date < NOW() AS is_after_duedate FROM reservations r
              INNER JOIN users u on r.user_id = u.id
              INNER JOIN copies c on r.copy_id = c.id
              INNER JOIN books b on c.book_id = b.id
              INNER JOIN authors a on b.author_id = a.id
              WHERE r.return_date IS NULL AND u.id = ?
              ORDER BY r.reservation_date DESC";

    $result = $db->getResult($query, array($_SESSION["user_id"]));

    while ($res = $result->fetch_assoc()) {
        echo "<div class='notification'>";
        echo $res["is_after_duedate"] ? "<p>You haven't returned the book yet!</p>" : "<p>You borrowed a book on $res[reservation_date]</p>";

        echo <<< NOTIFICATION
                <div class="notification-content">
                    <div class="notification-info">
                        <p>$res[title]</p>
                        <p>by $res[author]</p>
        NOTIFICATION;

        echo $res["is_after_duedate"] ? "<p>due date was: $res[due_date]</p>" : "<p>due date is: $res[due_date]</p>";
        echo "</div></div></div>";
    }
}

$db->close();