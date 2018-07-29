<?php
include_once "db.php";
$data = array();
$VisitType = [];
$qry = odbc_exec($connection, "select * from VisitType");
if (odbc_num_rows($qry) > 0) {
        while ($row = odbc_fetch_array($qry)) {
            $VisitType [] = $row;
        }
        $data = $VisitType;
		if ($data != null) {
			$response["Status"] = "Success";
			$response["data"] = $data;
		} else {
			$response["Status"] = "Success";
			$response["data"] = "No data";
		}
		header('Content-Type: application/json');
		$endoded = json_encode($response, JSON_PRETTY_PRINT);
		echo $endoded;
} else {
    $failure = "Error Fetching data at the moment..";
    $response = array('Status' => false, 'Message' => $failure);
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>