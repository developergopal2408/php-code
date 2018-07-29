<?php
ob_start();
session_start();
require_once 'db.php';
if (!isset($_SESSION['id'])) {
   header('Location:login.php');
}
?>
