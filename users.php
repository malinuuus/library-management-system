<h3>Users list</h3>
<div class="users">
    <div class="search-bar">
        <label for="search-bar">🔍</label>
        <input type="text" id="search-bar" placeholder="Search...">
    </div>
    <table class="table users-table">
        <tr>
            <th>First name</th>
            <th>Last name</th>
            <th>Admin rights</th>
            <th>Number of borrowed books</th>
        </tr>

        <?php
        require_once "classes/Database.php";
        $db = new Database("library_db");
        $result = $db->getResult("SELECT id FROM users");

        while ($userResult = $result->fetch_assoc()) {
            $user = new User($userResult["id"]);
            $admin = $user->isAdmin ? "admin" : "";
            $booksCount = $user->borrowed_books_count();

            echo <<< USER
                <tr class="user-info" onclick="window.location='index.php?page=userProfile&id=$user->id'">
                    <td class="user-info-first-name">$user->firstName</td>
                    <td class="user-info-last-name">$user->lastName</td>
                    <td class="user-info-admin">$admin</td>
                    <td class="user-info-books">$booksCount</td>
                </tr>
            USER;
        }
        ?>
    </table>
</div>
<script src="js/filterData.js"></script>