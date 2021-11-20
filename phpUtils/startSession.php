<?php
    // import this file before using $_SESSION
    // to store session variables
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
