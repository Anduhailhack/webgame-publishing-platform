<?php
if (PHP_SESSION_ACTIVE != session_status())
    session_start();

include_once("../utils/globals.php");
include_once("../utils/validators.php");
include_once("../db/conn.php");


if (!isset($_POST["email"]) || !isset($_POST["pass"])) {
    define("LOGIN_WARNING", "Email or Password not set.");
    header("Location: login.php");
    die("Bad request.");
}

$email = $_POST["email"];
$pass = hash("sha256", $_POST["pass"] + SALT);

if (isValidEmail($email) == 1) {
    try {
        $db = new DbConnection(DSN, USER, PASS);
        $value = $db->getUser($email, $pass);
        if ($value && count($value) >= 1) {
            $_SESSION["email"] = $value["user"];
            $_SESSION["user_id"] = $value["user_id"];
            header("Location: main.php");
        } else {
            session_destroy();
            define("LOGIN_WARNING", "Email or Password incorrect.");
            header("Location: login.php");
        }
    } catch (Exception $ex) {
        header("Location: internal_error.php");
        die("Unexpected internal error");
    }
}
