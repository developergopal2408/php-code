<?php
date_default_timezone_set("Asia/Kathmandu");
error_reporting(0);

$data = file_get_contents('php://input');
$json_data = json_decode($data);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db.php';
    $response = "";
    $failure = "";
    if ($data == "") {
        $response = array('status' => false, 'Message' => 'No Data Available To Process');
    } else {
        odbc_autocommit($connection, FALSE);
		$CenterNo = $json_data->CenterNo;
		$CollStatus = $json_data->CollStatus;
		$Discipline = $json_data->Discipline;
		$Latitude = $json_data->Latitude;
		$Longitude = $json_data->Longitude;
		$MeetingDate = $json_data->MeetingDate;
		$MeetingEndTime = $json_data->MeetingEndTime;
		$MeetingStartTime = $json_data->MeetingStartTime;
		$MeetingTime = $json_data->MeetingTime;
		$OfficeID = $json_data->OfficeID;
		$PresentMember = $json_data->PresentMember;
		$Remarks = $json_data->Remarks;
        $StaffID = $json_data->StaffID;
        $VisitedOfficeID = $json_data->VisitedOfficeID;
        $PostedDate = date('Y-m-d H:i:s');
		
		if (!empty($StaffID  AND $OfficeID)) {
            $insert = odbc_exec($connection, "INSERT INTO StaffFieldVisit(StaffID,OfficeID,VisitedOfficeID,CenterNo,MeetingDate,"
				. "MeetingTime,MeetingStartTime,MeetingEndTime,PresentMember,TotalMember,Borrowers,CollStatus,Discipline,Remarks,Latitude,Longitude,PostedDate)"
				. " VALUES ('$StaffID','$OfficeID','$VisitedOfficeID','$CenterNo','$MeetingDate','$MeetingTime',"
				. "'$MeetingStartTime','$MeetingEndTime','$PresentMember','0','0','$CollStatus','$Discipline','$Remarks','$Latitude','$Longitude','$PostedDate')")
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