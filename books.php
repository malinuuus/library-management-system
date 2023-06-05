<?php
// if $_GET["category"] is null assign ""
$searchValue = $_GET["category"] ?? "";
?>
<h3>Books</h3>
<div class="books-header header">
    <div class="search-bar">
        <label class="search-bar-label" for="search-bar">🔍</label>
        <input type="text" id="search-bar" value="<?php echo $searchValue; ?>" placeholder="Search...">
    </div>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "classes/User.php";
    $user = new User($_SESSION["user_id"]);

    if ($user->isAdmin) {
        echo "<a href='book.php?mode=add'>Add a book</a>";
    }
    ?>
</div>
<table class="table books-table">
    <tr class="table-header">
        <th></th>
        <th>title</th>
        <th>author</th>
        <th>category</th>
        <th>No. of available copies</th>
        <th></th>
    </tr>
</table>
<div class="load-btn">
    <button id="load-books" class="btn active">Load more books</button>
</div>
<?php
require_once "notificationmodal.php";
?>
<script src="js/filterData.js"></script>
<script src="js/modal.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    const booksCount = 6;
    const tableHeader = $('.books-table .table-header');

    function loadBooks(booksOffset, isSearching = false) {
        $.post('scripts/load_books.php', {
            booksOffset: booksOffset,
            booksCount: booksCount,
            searchValue: $('#search-bar').val()
        }, (data) => {
            if (isSearching) {
                $('.books-table').empty().append([tableHeader, data]);
            } else {
                removeModalListeners();
                $('.books-table').append(data);
            }
            addModalListeners();
        });
    }

    $(document).ready(() => {
        let booksOffset = 0;
        loadBooks(booksOffset);

        $('#load-books').click(() => {
            booksOffset += booksCount;
            loadBooks(booksOffset);
        });

        $('#search-bar').keyup(() => {
            loadBooks(booksOffset, true)
        });
    });
</script>