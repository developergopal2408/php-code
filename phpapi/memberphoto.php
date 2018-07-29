<?php

include_once "db.php";
$RegNo = $_REQUEST['RegNo'];
$PinCode = $_REQUEST['PinCode'];
$data = array();
$MemberPhoto = [];
$query = "select * from member where RegNo = '$RegNo' AND PinCode = '$PinCode'";
$result = odbc_exec($connection, $query);
if (odbc_num_rows($result) > 0) {
    $row = odbc_fetch_array($result);
    $OfficeID = $row['OfficeID'];
    $MemberID = $row['MemberID'];
    $CenterID = $row['CenterID'];
    $RegDate = $row['RegDate'];
    $FirstName = $row['FirstName'];
    $LastName = $row['LastName'];
    $select = odbc_exec($connection, "select Name From OfficeDetail WHERE  ID = '$OfficeID' ");
    $orow = odbc_fetch_array($select);
    $OName = $orow['Name'];
    $Photo = $row['Photo'];
    $url = "//10.0.1.4/d$/FinlitexImages/images/" . $Photo;
    $type = pathinfo($url, PATHINFO_EXTENSION);
    $Photos = file_get_contents($url);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($Photos);
    $data = array(
        'OfficeID' => $OfficeID,
        'MemberID' => $MemberID,
        'CenterID' => $CenterID,
        'FirstName' => $FirstName,
        'LastName' => $LastName,
        'RegDate' => $RegDate,
        'OName' => $OName,
        'Photo' => $base64
    );
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
    $failure = "तपाइको पासवोर्ड परिवर्तन भएको छ | कृपया लग आउट गरि पुन लग इन गर्नुहोला |";
    $response = array('Status' => false, 'Message' => $failure);
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>