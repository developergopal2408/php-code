<?php

include_once "db.php";
$MemberID = $_REQUEST['MemberID'];
$OfficeID = $_REQUEST['OfficeID'];
$PinCode = $_REQUEST['PinCode'];
$MasterID = $_REQUEST['MasterID'];
$data = array();
$qry = odbc_exec($connection, "select * from member where OfficeID = '$OfficeID' AND MemberID = '$MemberID' AND PinCode = '$PinCode'");
if (odbc_num_rows($qry) > 0) {
    $result = '';
    $qry = '';
    $qry = "SELECT * FROM CollSavingDetail WHERE MemberID=" . $MemberID . " AND MasterID=" . $MasterID;
    $result = odbc_exec($connection, $qry);
    $return = [];
    while ($row = odbc_fetch_array($result)) {
        $return[] = [
            'SavingType' => $row['SavingType'],
            'AccountNo' => $row['AccountNo'],
            'CrAmount' => $row['CrAmount'],
            'Cr' => $row['Cr'],
            'PreDue' => $row['PreDue']
        ];
    }
    $data['Saving'] = $return;

    $result = '';
    $qry = '';
    $qry = "SELECT * FROM CollLoanDetail WHERE MemberID=" . $MemberID . " AND MasterID=" . $MasterID;
    $result = odbc_exec($connection, $qry);
    $return = [];
    while ($row = odbc_fetch_array($result)) {
        $return[] = [
            'LoanType' => $row['LoanType'],
            'LoanNo' => $row['LoanNo'],
            'LoanInsNo' => $row['LoanInsNo'],
            'LoanPri' => $row['LoanPri'],
            'LoanInt' => $row['LoanInt'],
            'LoanCr' => $row['LoanCr'],
            'IntCr' => $row['IntCr'],
            'PriDue' => $row['PriDue'],
            'IntDue' => $row['IntDue']
        ];
    }
    $data['Loan'] = $return;

    $result = '';
    $qry = '';
    $qry = "SELECT * FROM CollInsuranceDetail WHERE MemberID=" . $MemberID . " AND MasterID=" . $MasterID;
    $result = odbc_exec($connection, $qry);
    $return = [];
    while ($row = odbc_fetch_array($result)) {
        $return[] = [
            'InsuranceType' => $row['InsuranceType'],
            'AccountNo' => $row['AccountNo'],
            'CrAmount' => $row['CrAmount'],
            'Cr' => $row['Cr'],
            'PreDue' => $row['PreDue']
        ];
    }
    $data['Fund'] = $return;

    /* $result = '';
      $qry = '';
      $qry = "SELECT * FROM collbalance WHERE MemberID=" . $MemberID . " AND MasterID=" . $MasterID;
      $result = odbc_exec($connection, $qry);
      $return = [];
      while ($row = odbc_fetch_array($result)) {
      $return[] = [
      'Balance' => $row['Balance'],
      'Deposit' => $row['Deposit'],
      'ExpectedAmt' => $row['ExpectedAmt'],
      'NetAmount' => $row['NetAmount'],
      'Withdraw' => $row['Withdraw'],
      'IsPaid' => $row['IsPaid'],
      'IsPresent' => $row['IsPresent']
      ];
      }
      $data['CollBalance'] = $return; */


    $response["Status"] = "Success";
    $response["data"] = $data;
} else {
    $failure = "तपाइको पासवोर्ड परिवर्तन भएको छ | कृपया लग आउट गरि पुन लग इन गर्नुहोला |";
    $response = array('Status' => false, 'Message' => $failure);
}
$endoded = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
echo $endoded;
?>