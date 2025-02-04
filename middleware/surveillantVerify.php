<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['authUser']->role != "Surveillant") header('location: error/404.php');
