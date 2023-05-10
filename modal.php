<?php
function showModal($authorId) {
    echo <<< MODAL
        <div class="delete-modal">
            <div class="delete-modal-content">
                <p>Are you sure you want to delete this author?</p>
                <div class="modal-options">
                    <button class="modal-cancel">❌</button>
                    <form action="scripts/deleteauthor.php" method="post">
                        <input type="hidden" name="author_id" value="$authorId">
                        <button type="submit">✅</button>
                    </form>
                </div>
            </div>
        </div>
    MODAL;
}

