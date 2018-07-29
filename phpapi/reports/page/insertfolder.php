<?php

include_once "top.php";
$date = date('Y/m/d H:i:s');
//$Uploadedtime = date('H:i:s', strtotime($date));
$con = mysqli_connect("localhost", "root", "", "file_management");
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $folder = "upload";
    $dir = "D:/" . $folder . "/";

// ----------------security-----------------
    function clean($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

//Function to get extension of the file
    function findexts($file) {
        $file = strtolower($file);
        $exts = explode(".", $file);
        $n = count($exts) - 1;
        $exts = $exts[$n];
        return $exts;
    }

// ----------prepare for new contract name with year or without---------

    If (!is_dir($dir)) {
        mkdir($dir);
        chmod($dir, 0777);
    }
    /* -- For Report Post -- */

    if (empty($_POST["reporttype"])) {
        $reporttype = clean($_POST["reporttype"]);
    } else {
        $reporttype = clean($_POST["reporttype"]);
    }
    If (!file_exists($dir . $reporttype)) {
        mkdir($dir . $reporttype);
        chmod($dir . $reporttype, 077);
    } else {
        $reporttype = $_POST["reporttype"];
    }

    /* -- End For Report Post -- */

    /* -- For FYear Post -- */
    if (empty($_POST["year"])) {
        $year = clean($_POST["year"]);
    } else {
        $year = clean($_POST["year"]);
    }
    If (!file_exists($dir . $reporttype . "/" . $year)) {
        mkdir($dir . $reporttype . "/" . $year);
        chmod($dir . $reporttype . "/" . $year, 077);
    } else {
        $year = $_POST["year"];
    }
    /* -- End For Fyear Post -- */
    /* -- For Month Post -- */
    if (empty($_POST["month"])) {
        $month = clean($_POST["month"]);
    } else {
        $month = clean($_POST["month"]);
    }
    If (!file_exists($dir . $reporttype . "/" . $year . "/" . $month)) {
        mkdir($dir . $reporttype . "/" . $year . "/" . $month);
        chmod($dir . $reporttype . "/" . $year . "/" . $month, 0777);
    } else {
        $month = $_POST["month"];
    }

    $file_type = $_FILES['uploaded_file']['type']; //returns the mimetype
    $allowed = array("image/jpeg", "image/png", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/pdf", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");


    if ((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0) && in_array($file_type, $allowed)) {
        //Check if the file is rar folder 
        $filename = $_FILES['uploaded_file']['name'];
        $ext = findexts($filename);
        $bcode = $branchcode . "." . $ext;
        $name = $_POST['filename'];
        $fname = $name . "." . $ext;

        if ($reporttype == "Monthly_Report") {
            $monthpath = "upload/" . $reporttype . "/" . $year . "/" . $month . "/";
            $folderpath = "";
            $fname = $bcode;
            $name = $branchcode;
            //Determine the path to which we want to save this file
            $newname = $dir . $reporttype . "/" . $year . "/" . $month . "/" . str_replace(" ", "_", $fname);
        } else {
            $monthpath = "";
            $folderpath = "upload/" . $reporttype . "/" . $year . "/";
            $newname = $dir . $reporttype . "/" . $year . "/" . str_replace(" ", "_", $fname);
        }


        $con->autocommit(FALSE);
        //Check if the file with the same name is already exists on the server
        if (!file_exists($newname)) {
            $filename = $_FILES['uploaded_file']['name'];
            $ext = findexts($filename);
            $bcode = $branchcode . "." . $ext;
            $name = $_POST['filename'];
            $fname = $name . "." . $ext;

            if ($reporttype == "Monthly_Report") {
                $fname = $bcode;
                $name = $branchcode;
                //Determine the path to which we want to save this file
                $newname = $dir . $reporttype . "/" . $year . "/" . $month . "/" . str_replace(" ", "_", $fname);
            } else {
                $newname = $dir . $reporttype . "/" . $year . "/" . str_replace(" ", "_", $fname);
            }

            if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $newname)) {

                if ($BranchID > 1) {
                    mysqli_query($con, "INSERT INTO document"
                            . "(BranchID,BranchCode,StaffID,ToBranchID,FiscalYear,Document_Type,Document_Month,Document_Name,"
                            . "SaveDateBS,SaveDateAD,Document_Path_Month,Document_Path_Folder,Uploaded_Time,FileName,Remarks,DepartmentID)"
                            . "VALUES('$BranchID','$branchcode','" . $_SESSION['StaffID'] . "','1','$year','$reporttype','$month','" . str_replace(" ", "_", $name) . "','$cdate',"
                            . "'" . date('Y/m/d') . "','$monthpath','$folderpath','$date','" . str_replace(" ", "_", $fname) . "','" . $_POST['remarks'] . "','" . $_POST['departid'] . "')");
                    $con->commit();
                } else {
                    foreach($_POST['ToBranchID'] as $Brancids) {
                        mysqli_query($con, "INSERT INTO document"
                                . "(BranchID,BranchCode,StaffID,ToBranchID,FiscalYear,Document_Type,Document_Month,Document_Name,"
                                . "SaveDateBS,SaveDateAD,Document_Path_Month,Document_Path_Folder,Uploaded_Time,FileName_Original,FileName,Remarks,DepartmentID)"
                                . "VALUES('$BranchID','$branchcode','" . $_SESSION['StaffID'] . "','$Brancids','$year','$reporttype','$month','" . str_replace(" ", "_", $name) . "','$cdate',"
                                . "'" . date('Y/m/d') . "','$monthpath','$folderpath','$date','$name','" . str_replace(" ", "_", $fname) . "','" . $_POST['remarks'] . "','" . $_SESSION['DepartmentID'] . "')");
                        $con->commit();
                    }
                }
            }
            echo "<script language='javascript'>
	alert('New Document From $branchName was added successfully');
	window.location = 'createfile.php';
	</script>";
        } else {
            echo "Error: File " . $bcode . " already exists";
        }
    } else {
        //$error_message = 'Only jpg, gif, and pdf files are allowed.';
        echo "<script language='javascript'>
	alert('Only jpg, gif, and pdf files are allowed.');
	window.location = 'createfile.php';
	</script>";
    }
}
?> 

