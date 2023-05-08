<?php
if (isset($_SESSION["err"])) {
    echo <<< MODAL
        <div class="modal">
            <div class="modal-content">
                <p>$_SESSION[err]</p>
                <span class="close">❌</span>
            </div>
        </div>
    MODAL;
    unset($_SESSION["err"]);
}