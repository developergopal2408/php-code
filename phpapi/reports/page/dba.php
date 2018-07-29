<?php

   $serverName = "JBS-DB"; //serverName\instanceName

   // Since UID and PWD are not specified in the $connectionInfo array,
   // The connection will be attempted using Windows Authentication.
   $connectionInfo = array( "Database"=>"FinliteX");
   $connection = sqlsrv_connect( $serverName, $connectionInfo);

   if( ! $connection ) {
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
   }
   
   
   
   
   
?>