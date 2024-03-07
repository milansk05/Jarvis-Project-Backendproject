<?php
session_start();

if (!isset($_SESSION['gebruikersnaam'])) {
    header('Location: login.php');
    exit();
}
