<?php

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, X-Auth-Token, Accept, Authorization");

	$userid=0;
	$branchid=0;
	$positionid=0;
    include("config.php");
if(isset($_POST['username']) && isset($_POST['password'])) {
	$user=$_POST['username'];
	$pass=$_POST['password'];
	$qry = "SELECT StaffID,Code,BranchID,FirstName,LastName,Photo FROM StaffMain WHERE StatusID=1 AND Code= '".$user. "' AND mobile ='".$pass."'";
	$json = getRecords($qry);
	if(count($json)===0){
		$response["Status"]="Failed";
		$response["responseText"]="Invalid Username or Password ";
	}else{
		$userid= $json[0]['StaffID'];
		$branchid= $json[0]['BranchID'];
		$imgfile=$json[0]['Photo'];
		$user=$json;
		$bQuery="SELECT ID,Name FROM OfficeDetail  WHERE ID =".$branchid;
		$userbranch=getRecords($bQuery);
		$data=array();
		$data['User']=$user;
		//$data['photo']= get_image($imgfile,"images/StaffPhoto/");
		$data['photo']= get_image($imgfile,"D:Apps/Staff Photo/");
		$data['UserOffice']=$userbranch;
		$response["Status"]="Success";
		$response["responseText"]=$data;
	}
	$endoded= json_encode($response);
	echo $endoded;
}else{
	$response["Status"]="Failed";
	$response["responseText"]="No User Data Feeded";
	$endoded= json_encode($response);
	echo $endoded;
	}
?>