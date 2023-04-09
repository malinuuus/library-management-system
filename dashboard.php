<h3>Dashboard</h3>
<h3>Notifications:</h3>
<?php
require_once "classes/Database.php";
$db = new Database("library_db");

$query = "SELECT CONCAT(u.first_name, ' ' ,u.last_name) as user, DATE_FORMAT(r.reservation_date, '%Y-%m-%d') as reservation_date, b.title, CONCAT(a.first_name, ' ', a.last_name) as author, c.id as copy_id FROM reservations r
          INNER JOIN users u on r.user_id = u.id
          INNER JOIN copies c on r.copy_id = c.id
          INNER JOIN books b on c.book_id = b.id
          INNER JOIN authors a on b.author_id = a.id";

$result = $db->getResult($query);

while ($res = $result->fetch_assoc()) {
    echo <<< NOTIFICATION
        <div class="notification">
            <p>$res[user] borrowed a book on $res[reservation_date]</p>
            <div class="notification-info">
                <p>$res[title]</p>
                <p>by $res[author]</p>
                <p>id of copy: $res[copy_id]</p>
            </div>
        </div>
    NOTIFICATION;
}