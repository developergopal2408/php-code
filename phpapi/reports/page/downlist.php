<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(0);

function myUrlEncode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
}

$con = mysqli_connect("localhost", "root", "", "file_management");
$query = "select * from document where Notify_ho = '0' order by Id Desc";
$runs = mysqli_query($con, $query);
$row = mysqli_fetch_array($runs);
$month = $row['Document_Month'];
$path = $_GET['path'];
$file = $_GET['file'];
if ($month == true) {
    $fileurl = $path."/$file";
} else {
    $fileurl = $path."/$file";
}
$files = myUrlEncode($fileurl);
if (!$file) { // file does not exist
    die('file not found');
} else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$file");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");
    // read the file from disk
    readfile($files);
}