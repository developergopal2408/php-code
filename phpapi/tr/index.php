<?php
ob_start();
session_start();
require_once 'dba.php';
if (!isset($_SESSION['id'])) {
   header('Location:login.php');
}
?>
