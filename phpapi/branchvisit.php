<?php

error_reporting(0);
$data = file_get_contents('php://input');
$json_data = json_decode($data);
//print_r($json_data);
$P = date('Y-m-d H:i:s');
$fp = fopen('json/'.$P.'json', 'w');
fwrite($fp, json_encode($json_data));
fclose($fp);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db.php';
    $response = "";
    $failure = "";
    if ($data == "") {
        $response = array('status' => false, 'Message' => 'No Data Available To Process');
    } else {
        odbc_autocommit($connection, FALSE);
        $StaffID = $json_data->StaffID;
        $OfficeID = $json_data->OfficeID;
        $VisitedOfficeID = $json_data->VisitedOfficeID;
        $VisitDate = $json_data->VisitDate;
        $VisitTime = $json_data->VisitTime;
        $VisitTypeID = $json_data->VisitTypeID;
        $VisitEndTime = $json_data->VisitEndTime;
        $VisitEndDate = $json_data->VisitEndDate;
        $Remarks = $json_data->Remarks;
        $Latitude = $json_data->Latitude;
        $Longitude = $json_data->Longitude;
		$CLatitude = $json_data->CLatitude;
        $CLongitude = $json_data->CLongitude;
        date_default_timezone_set("Asia/Kathmandu");
        $PostedDate = date('Y-m-d H:i:s');
		

        if (!empty($StaffID  AND $OfficeID AND $VisitedOfficeID  AND $VisitDate AND $VisitTime  AND  $Remarks AND $Latitude AND $Longitude )) {
            $insert = odbc_exec($connection, "INSERT INTO StaffBranchVisit(StaffID,OfficeID,VisitedOfficeID,VisitDate,"
                    . "VisitEndDate,VisitTime,VisitEndTime,VisitTypeID,"
                    . "Remarks,Latitude,Longitude,CLatitude,CLongitude,PostedDate) VALUES"
                    . "('$StaffID','$OfficeID','$VisitedOfficeID','$VisitDate',"
                    . "'$VisitEndDate','$VisitTime','$VisitEndTime','$VisitTypeID',"
                    . "'$Remarks','$Latitude','$Longitude','$CLatitude','$CLongitude','$PostedDate')") 
					or die(print_r(odbc_errormsg($connection)));
			//print_r($insert);

            odbc_commit($connection);
            $response = array('status' => true, 'Message' => 'Successfully Uploaded');
        } else {

            $failure = "Error Uploading Data";
           odbc_rollback($connection);
            $response = array('status' => false, 'Message' => $failure);
        }
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}
?>