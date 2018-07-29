<?php
ob_start();
session_start();
require_once 'connect.php';
?>
<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>JBS | Remittance Claim Form</title>

        <link rel="icon" type="image/png" href="img/logo.png"/>
        <link href="https://fonts.googleapis.com/css?family=Oswald:300,400" rel="stylesheet">
        <!-- Bootstrap -->
        <script src="js/jquery-2.1.0.min.js" type="text/javascript"></script>

        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
        <!--<link href="css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>-->
        <link href="js/nepali.datepicker.v2.2/nepali.datepicker.v2.2.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/toastr.css" rel="stylesheet" type="text/css"/>
        <script src="js/tableExport.js" type="text/javascript"></script>
        <script src="js/jquery.base64.js" type="text/javascript"></script>
        <link href="css/animate.css" rel="stylesheet" type="text/css"/>



        <style>
            #row:hover{
                background-color:#39d;
                cursor:pointer;
            }



            .hvr-grow {
                color:#000;
                display: inline-block;
                vertical-align: middle;
                transform: translateZ(0);
                box-shadow: 0 0 1px rgba(0, 0, 0, 0);
                backface-visibility: hidden;
                -moz-osx-font-smoothing: grayscale;
                transition-duration: 0.3s;
                transition-property: transform;

            }

            .hvr-grow:hover,
            .hvr-grow:focus,
            .hvr-grow:active {
                transform: scale(1.1);

            }

            html {
                height: 100%;
                box-sizing: border-box;
            }

            *,
            *:before,
            *:after {
                box-sizing: inherit;
            }

            body {
                position: relative;
                margin:0px;

                padding-bottom: 6rem;
                min-height: 100%;
                font-family: "Helvetica Neue", Arial, sans-serif;
            }


            /**
             * Footer Styles
             */

            .footer {
                position: absolute;
                right: 0;
                bottom: 0;
                left: 0;
                padding: 1rem;
                background-color: #449D44;
                text-align: center;
                color:#FFF;
            }



            .vericaltext{
                width:1px;
                word-wrap: break-word;
                white-space:pre-wrap; 
                border:20px solid #CCC;
                text-align:center;

            }

            table.dataTable > thead > tr > th {
                padding-left: 8px;
                padding-right: 30px;
            }

        </style>
    </head>

