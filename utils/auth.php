<?php
if (session_status() != PHP_SESSION_ACTIVE)
    session_start();

if (!isset($_SESSION["email"]) && !isset($_SESSION["user_id"])) {
    echo '<script>window.location="login.php"</script>';
    die("Unauthorized");
}
