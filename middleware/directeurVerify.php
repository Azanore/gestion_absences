<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}if ($_SESSION['authUser']->role != "Directeur") header('location: error/404.php');
