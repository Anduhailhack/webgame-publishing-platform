<?php

function isValidEmail($email)
{
    return preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $email);
}
