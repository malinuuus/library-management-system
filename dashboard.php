<h3>Dashboard</h3>
<h3>Notifications:</h3>
<?php
require_once "classes/Database.php";
$db = new Database("library_db");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "classes/User.php";
$user = new User($_SESSION["user_id"]);

if ($user->isAdmin) {
    $query = "SELECT u.id AS user_id, u.first_name AS u_first_name, u.last_name AS u_last_name, r.reservation_date, r.due_date, b.title, a.first_name, a.last_name, r.id, c.id AS copy_id, r.due_date < NOW() AS is_after_duedate, b.image FROM reservations r
              INNER JOIN users u on r.user_id = u.id
              INNER JOIN copies c on r.copy_id = c.id
              INNER JOIN books b on c.book_id = b.id
              INNER JOIN authors a on b.author_id = a.id
              WHERE r.return_date IS NULL
              ORDER BY r.reservation_date DESC";

    $result = $db->getResult($query);
} else {
    $query = "SELECT r.reservation_date, r.due_date, b.title, a.first_name, a.last_name, r.due_date < NOW() AS is_after_duedate, b.image FROM reservations r
              INNER JOIN users u on r.user_id = u.id
              INNER JOIN copies c on r.copy_id = c.id
              INNER JOIN books b on c.book_id = b.id
              INNER JOIN authors a on b.author_id = a.id
              WHERE r.return_date IS NULL AND u.id = ?
              ORDER BY r.reservation_date DESC";

    $result = $db->getResult($query, array($_SESSION["user_id"]));
}

while ($res = $result->fetch_assoc()) {
    $reservationDate = DateTime::createFromFormat('Y-m-d H:i:s', $res["reservation_date"])->format('Y-m-d');
    echo "<div class='notification'>";

    if ($user->isAdmin) {
        $userLink = "<a href='index.php?page=userProfile&id=$res[user_id]'>$res[u_first_name] $res[u_last_name]</a>";
        echo $res["is_after_duedate"] ? "<p>$userLink hasn't returned the book yet!</p>" : "<p>$userLink borrowed a book on $reservationDate</p>";
    } else {
        echo $res["is_after_duedate"] ? "<p>You haven't returned the book yet!</p>" : "<p>You borrowed a book on $reservationDate</p>";
    }

    require_once "classes/File.php";
    $imagePath = (new File($res["image"]))->get_file("images/blank_book.jpg");

    echo <<< NOTIFICATION
            <div class="notification-content">
                <img src=$imagePath alt="book cover">
                <div class="notification-info">
                    <p class="title">$res[title]</p>
                    <p>by $res[first_name] $res[last_name]</p>
    NOTIFICATION;

    if ($user->isAdmin) {
        echo "<p>id of copy: $res[copy_id]</p>";
    }

    echo $res["is_after_duedate"] ? "<p>due date was: $res[due_date]</p>" : "<p>due date is: $res[due_date]</p>";

    if ($user->isAdmin) {
        echo <<< NOTIFICATION
                    </div>
                    <form action="scripts/returnbook.php" method="post">
                        <input type="hidden" name="reservation_id" value="$res[id]">
                        <button type="submit">Book returned</button>
                    </form>
                </div>
            </div>
        NOTIFICATION;
    } else {
        echo "</div></div></div>";
    }
}