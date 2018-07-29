<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$path = $_GET['path'];
if (!$path) { // file does not exist
    die('file not found');
} else {
    header("Content-type: application/pdf");
    header("Content-Disposition: inline; filename=$path");
    @readfile($path);
}