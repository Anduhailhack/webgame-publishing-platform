<?php
if (session_status() != PHP_SESSION_ACTIVE)
    session_start();

define("PAGE_TITLE", "Home");
include_once("includes/header.php");
include_once("./utils/auth.php");

include_once("includes/home_aside.php");
include_once("includes/home_main.php");


include_once("includes/footer.php");
