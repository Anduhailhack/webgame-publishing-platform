<?php
if (session_start() != PHP_SESSION_ACTIVE)
    session_start();

define("PAGE_TITLE", "Login");
include_once("includes/header.php");

include_once("includes/login.php");

include_once("includes/footer.php");
