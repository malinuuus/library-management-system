<?php
function showModal($itemType, $id) {
    echo <<< MODAL
        <div class="delete-modal hide">
            <div class="delete-modal-content">
                <p>Are you sure you want to delete this $itemType?</p>
                <div class="modal-options">
                    <button class="modal-cancel">❌</button>
    MODAL;

    if ($itemType === 'author') {
        echo <<< MODAL
            <form action="scripts/deleteauthor.php" method="post">
                <input type="hidden" name="author_id" value="$id">
                <button type="submit" class="modal-accept">✅</button>
            </form>
        MODAL;
    } else if ($itemType === 'book') {
        echo <<< MODAL
            <form action="scripts/deletebook.php" method="post">
                <input type="hidden" name="book_id" value="$id">
                <button type="submit" class="modal-accept">✅</button>
            </form>
        MODAL;
    }
    echo "</div></div></div>";
}

