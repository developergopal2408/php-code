<?php
include_once 'top.php';
include_once 'header.php';
?>
<!-- Site wrapper -->
<div class="wrapper">
    <style>
        .select2-choice { background-color: #00f; }
    </style>
    <?php
    include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
    include_once 'sidebar.php'; //Include Sidebar.php-->
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>
                <small>Upload Monthly Report</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Upload Monthly Report</li>
            </ol>
        </section>
        <!-- Main content -->

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-3">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <span class="text-bold"><i class="fa fa-folder-o"></i> Add Files</span>
                            </div>
                            <div class="box-body">
                                <?php
                                if ($BranchID == 1) {
                                    ?>
                                    <!-- search form -->
                                    <form name="goo"  action="insertfolder.php" method="post" class="form-horizontal" enctype="multipart/form-data" onsubmit="return ValidateForm()">
                                        <div class=" form-group">
                                            <div class="col-xs-12 form-group-sm">
                                                <input type="text" name="year" id="year" class="form-control" value="<?php echo $fis; ?>" readonly><br/>
                                            </div>
                                            <div class="col-xs-12 form-group-sm">
                                                <select name="reporttype" id="reporttype" required class="form-control select2"  style="width:220px;">
                                                    <option >Select Report Type</option>
                                                    <option value="Budget_Report" data-sync="2">Budget Report Upload</option>
                                                    <option value="Loan_Files" data-sync="3">Loan File Upload</option>
                                                    <option value="Insurance_Files" data-sync="4">Insurance File Upload</option>
                                                    <option value="Internal_Audit" data-sync="5">Audit File Upload</option>
                                                    <option value="Rectified_Remarks" data-sync="6">Rectified Remark(कैफियत सुधार)</option>
                                                    <option value="Other" >Miscellenous File Upload</option>
                                                    <?php
                                                    if ($StaffID == 4 or $StaffID == 15) {
                                                        ?>
                                                        <option value="Circular" >Circular Upload</option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select><br/><br/>
                                            </div>
                                            <div class="col-xs-12 form-group-sm">
                                                <select name="ToBranchID[]" id="ToBranchID" class="form-control select2" multiple="multiple" required style="width:220px;">
                                                    <option value="select">Select Branch</option>
                                                    <option value="0">All Branch</option>
                                                    <?php
                                                    $sql = odbc_exec($connection, "select * from officedetail where ID > 1");
                                                    while ($row = odbc_fetch_array($sql)) {
                                                        ?>
                                                        <option value="<?php echo $row['ID']; ?>" ><?php echo $row['Code'] . " - " . $row['Name']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select><br/><br/>
                                            </div>


                                            <div class="col-xs-12 form-group-sm">
                                                <input type="text" name="filename" id="filename" class="form-control" placeholder="Enter Subject"><br/>
                                            </div>

                                            <div class="col-xs-12">
                                                <input type="file" name="uploaded_file" id="uploaded_file"  class="form-control"><br/><br/>
                                            </div>

                                            <div class="col-xs-12">
                                                <textarea name="remarks" id="remarks"  class="form-control required" placeholder="Enter Description"></textarea><br/><br/>
                                            </div>

                                            <div class="col-xs-12">
                                                <input type="submit"  value="Submit" id="submit" class=" btn btn-sm bg-green pull-right">
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                } else {
                                    ?>
                                    <!-- search form -->
                                    <form name="goo"  action="insertfolder.php" method="post" class="form-horizontal" enctype="multipart/form-data" onsubmit="return ValidateForm()">
                                        <div class=" form-group">
                                            <div class="col-xs-12 form-group-sm">
                                                <input type="text" name="year" id="year" class="form-control" value="<?php echo $fis; ?>" readonly><br/>
                                            </div>
                                            <div class="col-xs-12 form-group-sm">
                                                <select name="reporttype" id="reporttype" required class="form-control select2"  style="width:220px;" >
                                                    <option>Select Report Type</option>
                                                    <option value="Monthly_Report" data-sync="1">Monthly Report Upload</option>
                                                    <option value="Budget_Report" data-sync="2">Budget Report Upload</option>
                                                    <option value="Loan_Files" data-sync="3">Loan File Upload</option>
                                                    <option value="Insurance_Files" data-sync="4">Insurance File Upload</option>
                                                    <option value="Internal_Audit" data-sync="5">Audit File Upload</option>
                                                    <option value="Rectified_Remarks" data-sync="6">Rectified Remark(कैफियत सुधार)</option>
                                                    <option value="Other" >Miscellenous File Upload</option>
                                                </select><br/><br/>
                                            </div>
                                            <div class="col-xs-12 form-group-sm">
                                                <select name="departid" id="departid" class="form-control select2" required style="width:220px;">
                                                    <option value="select">Select To Department</option>
                                                    <?php
                                                    $sql = odbc_exec($connection, "select * from Department where DepartmentID > '0' order by DepartmentID");
                                                    while ($row = odbc_fetch_array($sql)) {
                                                        ?>
                                                        <option value="<?php echo $row['DepartmentID']; ?>" ><?php echo $row['DepartmentName']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select><br/><br/>
                                            </div>

                                            <div class="col-xs-12">
                                                <select name="month" id="month" required class="form-control select2" style="width:220px;">
                                                    <option>Select Month Folder</option>
                                                    <option value="Baishak">Baishak</option>
                                                    <option value="Jestha">Jestha</option>
                                                    <option value="Ashad">Ashad</option>
                                                    <option value="Shrawan">Shrawan</option>
                                                    <option value="Bhadra">Bhadra</option>
                                                    <option value="Ashwin">Ashwin</option>
                                                    <option value="Karthik">Karthik</option>
                                                    <option value="Mangsir">Mangsir</option>
                                                    <option value="Poush">Poush</option>
                                                    <option value="Magh">Magh</option>
                                                    <option value="Falgun">Falgun</option>
                                                    <option value="Chaitra">Chaitra</option>
                                                </select><br/><br/>
                                            </div>

                                            <div class="col-xs-12 form-group-sm">
                                                <input type="text" name="filename" id="filename"  class="form-control textbox" placeholder="Enter File Name without space" pattern="^\S+$" /><br/>
                                            </div>

                                            <div class="col-xs-12">
                                                <input type="file" name="uploaded_file" id="uploaded_file"  class="form-control"><br/><br/>
                                            </div>

                                            <div class="col-xs-12">
                                                <textarea name="remarks" id="remarks"  class="form-control required"></textarea><br/><br/>
                                            </div>

                                            <div class="col-xs-12">
                                                <input type="submit"  value="Submit" id="submit" class=" btn btn-sm bg-green pull-right">
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                }
                                ?>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-9">
                        <div class="box box-solid">
                            <div class="box-header with-border bg-green-active">
                                <span class="text-bold"><i class="fa fa-file-archive-o"></i> View Your Files</span>
                            </div>
                            <div class="box-body">
                                <table id="daybook2" class="table table-bordered " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>SNo</th>
                                            <th>Other Reports </th>
                                            <th>Branch Monthly File </th>
                                            <th>Download Your File </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        $con = mysqli_connect("localhost", "root", "", "file_management");
                                        $query = "select * from document where BranchID = '$BranchID'";
                                        $runs = mysqli_query($con, $query);
                                        while ($row = mysqli_fetch_array($runs)) {
                                            $dtype = $row['Document_Type'];
                                            $fname = $row['FileName'];
                                            if ($dtype == "Monthly_Report") {
                                                $path = $row['Document_Path_Month'];
                                            } else {
                                                $path = $row['Document_Path_Folder'];
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td><?php
                                                    if ($row['Document_Type'] != "Monthly_Report" AND $row['Document_Path_Folder'] != FALSE) {
                                                        echo $row['FileName'];
                                                    }
                                                    ?></td>
                                                <td><?php
                                                    if ($row['Document_Type'] = "Monthly_Report" AND $row['Document_Path_Month'] != FALSE) {
                                                        echo $row['FileName'];
                                                    }
                                                    ?></td>
                                                <td class="text-center"><a href="downlist.php?path=<?php echo "D:/" . $path; ?>&file=<?php echo $fname; ?>"><i class="fa fa-download"></i></a></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    <?php
    include_once 'copyright.php';
    ?>
</div>
<!-- ./wrapper -->
<?php
include_once 'footer.php';
?>

<script language='JavaScript'>
    var x = document.getElementById("month");
    var departid = document.getElementById("departid");
    x.disabled = true;
    var file = document.getElementById("filename");
    file.disabled = true;
    $('#reporttype').change(function () {
        var SelectedDD = $(this).val();
        if (SelectedDD == "Monthly_Report")
        {


            var x = document.getElementById("month");
            file.disabled = true;
            x.disabled = false;
            if (x == "") {
                alert("Please Select Month First");
                return false;
            }
        }
        if (SelectedDD != "Monthly_Report")
        {
            var x = document.getElementById("month");
            x.disabled = true;
            file.disabled = false;
        }
        if (SelectedDD == "Monthly_Report" || SelectedDD == "Budget_Report") {
            var x = document.getElementById("departid").selectedIndex = '5';
            var y = document.getElementById("departid").options;
            //alert("Index: " + y[x].index + " is " + y[x].text);
            $("#departid").append("<option value=" + y[x].index + " selected  >" + y[x].text + "</option>");

        }
        if (SelectedDD == "Loan_Files" || SelectedDD == "Insurance_Files") {
            $("#departid").load();
            var t = document.getElementById("departid").selectedIndex = '1';
            var y = document.getElementById("departid").options;
            $("#departid").append("<option value=" + y[t].index + " selected  >" + y[t].text + "</option>");

        }
        if (SelectedDD == "Internal_Audit") {
            $("#departid").load();
            var t = document.getElementById("departid").selectedIndex = '3';
            var y = document.getElementById("departid").options;
            $("#departid").append("<option value=" + y[t].index + " selected  >" + y[t].text + "</option>");

        }

    });

    function ValidateForm() {
        var Check = 0;

        var text = this.getElementById('filename').value;
        text = text.split(' '); //we split the string in an array of strings using     whitespace as separator
        return (text.length == 1);


        if (document.goo.reporttype.value == 'select') {
            Check = 1;
        }
        if (document.goo.uploaded_file.value == '') {
            Check = 2;
        }
        if (document.goo.remarks.value == '') {
            Check = 3;
        }
        if (document.goo.departid.value == 'select') {
            Check = 4;
        }


        if (Check == 1) {
            alert(" Please Select Report Type First ");
            return false;
        } else if (Check == 2) {
            alert(" Please Upload File First ");
            return false;
        } else if (Check == 3) {
            alert(" Please Write what you are uploading .. ");
            return false;

        } else if (Check == 4) {
            alert(" Please Select Department .. ");
            return false;

        } else {
            document.goo.submit.disabled = true;
            return true;
        }
    }

    $("#folder").DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'brtrip',
    });




</script>
