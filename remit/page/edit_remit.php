<?php
require_once 'top.php';
include_once 'header.php';
?>
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
    include_once 'sidebar.php'; //Include Sidebar.php-->
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <span class="text-bold">Remittance Payment</span>
                            <div class="box-tools pull-right"></div>
                        </div>
                        <?php
                        include_once 'db2.php';
                        $msgid = $_GET['edit'];
                        $query = "SELECT * FROM remittance WHERE MSGID = '$msgid'";
                        $run = mysqli_query($conn, $query);
                        $row = mysqli_fetch_array($run);
                        $serialno = $row['SERIALNO'];
                        $remitno = $row['REMITNO'];
                        $remit_co = $row['REMITCOMPANY'];
                        $rname = $row['RECEIVERNAME'];
                        $rfname = $row['RECEIVERFATHERNAME'];
                        $raddress = $row['RECEIVERADDRESS'];
                        $rdistrict = $row['RECEIVERDISTRICT'];
                        $idtype = $row['RECEIVERIDTYPE'];
                        $rissue = $row['RECEIVERIDISSUEDATE'];
                        $ridno = $row['RECEIVERIDNO'];
                        $rdob = $row['RECEIVERDOB'];
                        $rcontact = $row['RECEIVERCONTACTNO'];
                        $sname = $row['SENDERNAME'];
                        $scontact = $row['SENDERCONTACTNO'];
                        $scountry = $row['SENDERCOUNTRY'];
                        $srelation = $row['SENDERRELATION'];
                        $expamt = $row['EXPECTEDAMT'];
                        ?>
                        <div class="box-body">
                            <form class="form-horizontal" role="form" action="" method="post" name="remit" id="remit">
                                <div class="col-sm-6 ">
                                    <input type="hidden" class="form-control" id="serial"  name="serial" value="<?php echo $serialno; ?>">
                                    <div class="form-group form-group-sm">
                                        <label for="InputRemitNo" class="control-label col-xs-6">Remit Number</label>
                                        <div class="col-xs-6">
                                            <input type="text" maxlength="16" class="form-control" name="remit_no" id="remit_no" placeholder="Enter Remit Number" value="<?php echo $remitno; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm" >
                                        <label for="InputRemitCompany" class="control-label col-xs-6" >Remit Company</label>
                                        <div class="col-xs-6">
                                            <select name="rcompany" id="rcompany" class="form-control select2" required >
                                                <option value="select" >Select Remit Company</option>
                                                <?php
                                                $sql = odbc_exec($connection, "SELECT * FROM RemittanceList WHERE IsActive = 'Y'");
                                                while ($rcom = odbc_fetch_array($sql)) {
                                                    ?>
                                                    <option value="<?php echo $rcom['RemitName']; ?>" <?php
                                                    if ($remit_co == $rcom['RemitName']) {
                                                        echo 'selected';
                                                    }
                                                    ?> ><?php echo $rcom['RemitName']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputReceiverName" class="control-label col-xs-6">Receiver Name</label>
                                        <div class="col-xs-6">
                                            <input type="text" class="form-control" name="rname" id="rname" placeholder="Enter Receiver Name" value="<?php echo $rname; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputFReceiverName" class="control-label col-xs-6">Receiver's Father Name</label>
                                        <div class="col-xs-6">
                                            <input type="text" class="form-control" name="rfname" id="rfname" placeholder="Enter Receiver Father Name" value="<?php echo $rfname; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputReceiverAdd" class="control-label col-xs-6">Receiver Address</label>
                                        <div class="col-xs-6">
                                            <input type="text" class="form-control" name="raddress" id="raddress" placeholder="Enter Receiver Address" value="<?php echo $raddress; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputReceiverDist" class="control-label col-xs-6">Receiver District</label>
                                        <div class="col-xs-6">
                                            <input type="text" class="form-control" name="district" id="district" placeholder="Enter Receiver District" value="<?php echo $rdistrict; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputIDTYPE" class="control-label col-xs-6">Receiver ID TYPE</label>
                                        <div class="col-xs-6">
                                            <select name="sidtype" id="sidtype" class="form-control select2" required >
                                                <option value="select" >Select ID TYPE</option>
                                                <?php
                                                $sql1 = odbc_exec($connection, "SELECT * FROM IdentityType");
                                                while ($sid = odbc_fetch_array($sql1)) {
                                                    ?>
                                                    <option value="<?php echo $sid['IdentityType']; ?>" <?php
                                                    if ($idtype == $sid['IdentityType']) {
                                                        echo 'selected';
                                                    }
                                                    ?>   ><?php echo $sid['IdentityType']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputIDIssueDate" class="control-label col-xs-6">ID Issue Date</label>
                                        <div class="col-xs-6">
                                            <input type="text" maxlength="10" class="form-control" name="issue" id="issue" placeholder="Enter ID ISSUE DATE" value="<?php echo $rissue; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputIDNo" class="control-label col-xs-6">ID Number</label>
                                        <div class="col-xs-6">
                                            <input type="text" maxlength="10" class="form-control" name="idno" id="idno" placeholder="Enter ID Number" value="<?php echo $ridno; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputDOB" class="control-label col-xs-6">Date OF Birth</label>
                                        <div class="col-xs-6">
                                            <input type="text" maxlength="10" class="form-control" name="dob" id="dob" placeholder="Enter DOB Date" value="<?php echo $rdob; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputReceiverContact" class="control-label col-xs-6">Receiver Contact Number</label>
                                        <div class="col-xs-6">
                                            <input type="text" maxlength="10" class="form-control" name="rcontact" id="rcontact" placeholder="Enter Receiver Contact Number" value="<?php echo $rcontact; ?>" required>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-6">

                                    <div class="form-group form-group-sm">
                                        <label for="InputSenderName" class="control-label col-xs-6">Sender Name</label>
                                        <div class="col-xs-6">
                                            <input type="text" class="form-control" name="sname" id="sname" placeholder="Enter Sender Name" value="<?php echo $sname; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputSenderContact" class="control-label col-xs-6">Sender Contact Number</label>
                                        <div class="col-xs-6">
                                            <input type="text" maxlength="10" class="form-control" name="scontact" id="scontact" placeholder="Enter Sender Contact Number" value="<?php echo $scontact; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputRelation" class="control-label col-xs-6">Relation</label>
                                        <div class="col-xs-6">
                                            <select name="relation" id="relation" class="form-control select2" required >
                                                <option value="select" >Select Relation Type</option>
                                                <?php
                                                $sql2 = odbc_exec($connection, "SELECT * FROM relation where ID > 0");
                                                while ($r = odbc_fetch_array($sql2)) {
                                                    ?>
                                                    <option value="<?php echo $r['Relation']; ?>" <?php
                                                    if ($srelation == $r['Relation']) {
                                                        echo 'selected';
                                                    }
                                                    ?>><?php echo $r['Relation']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputSenderCountry" class="control-label col-xs-6">Sender Country</label>
                                        <div class="col-xs-6">
                                            <input type="text" class="form-control" name="country" id="country" placeholder="Enter Sender Country" value="<?php echo $scountry; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm">
                                        <label for="InputReceiverContact" class="control-label col-xs-6">Expected Amount</label>
                                        <div class="col-xs-6">
                                            <input type="text" class="form-control" name="expamount" id="expamount" placeholder="Enter Expected Amount" value="<?php echo $expamt; ?>" required>
                                        </div>
                                    </div>


                                </div>


                                <div class="col-md-12" style="margin-top:7px;">
                                    <div class="col-xs-6 pull-right">
                                        <button type="button" id="claim" name="claim"  class="btn btn-md btn-block btn-primary" style="margin-bottom:10px;" data-toggle="modal" data-target="#confirm-submit">Update Remit</button>
                                    </div>
                                    <div class="col-xs-6 pull-left">
                                        <a href="request_remit.php" class="btn btn-md btn-block bg-red">Reset</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->


            <div class="row">
                <div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form name="remit" id="remit" class="form-inline" role="form" action="remitprocess.php" method="post">
                                <div class="modal-body">
                                    <h4 class="text-primary">Are you sure you want to submit the following details?</h4>
                                    <div class="col-md-12" style="margin-top:30px;padding:20px;">
                                        <div class="col-xs-6">
                                            <!-- We display the details entered by the user here -->
                                            <table class="table table-striped responsive-table table-bordered">

                                                <tr>
                                                    <th>Remit No</th>
                                                    <td id="rno" name="rno"></td>
                                                </tr>
                                                <tr>
                                                    <th>Remit Company</th>
                                                    <td id="company" name="company"></td>
                                                </tr>
                                                <tr>
                                                    <th>Receiver Name</th>
                                                    <td id="name" name="name"></td>
                                                </tr>
                                                <tr>
                                                    <th>Receiver Father Name</th>
                                                    <td id="fname" name="fname"></td>
                                                </tr>
                                                <tr>
                                                    <th>Address</th>
                                                    <td id="addr" name="addr"></td>
                                                </tr>
                                                <tr>
                                                    <th> District</th>
                                                    <td id="dist" name="dist"></td>
                                                </tr>

                                                <tr>
                                                    <th>ID Type</th>
                                                    <td id="idtype" name="idtype"></td>
                                                </tr>

                                                <tr>
                                                    <th>Issue Date</th>
                                                    <td id="issuedate" name="issuedate"></td>
                                                </tr>
                                                <tr>
                                                    <th>ID No</th>
                                                    <td id="idnumber" name="idnumber"></td>
                                                </tr>
                                                <tr>
                                                    <th>Date of Birth</th>
                                                    <td id="dateofbirth" name="dateofbirth" ></td>
                                                </tr>
                                                <tr>
                                                    <th>Receiver Contact No</th>
                                                    <td id="receivecontact" name="receivecontact"></td>
                                                </tr>

                                            </table>

                                        </div>
                                        <div class="col-xs-6">
                                            <table class="table table-striped responsive-table table-bordered">
                                                <tr>
                                                    <th>Sender Name</th>
                                                    <td id="sendername" name="sendername"></td>
                                                </tr>
                                                <tr>
                                                    <th>Sender Contact No</th>
                                                    <td id="sendercontact" name="sendercontact"></td>
                                                </tr>
                                                <tr>
                                                    <th>Relation</th>
                                                    <td id="senderrelation" name="senderrelation"></td>
                                                </tr>
                                                <tr>
                                                    <th>Country</th>
                                                    <td id="sendercountry" name="sendercountry"></td>
                                                </tr>
                                                <tr>
                                                    <th>Expected Amount</th>
                                                    <td id="expectedamount" name="expectedamoount"></td>
                                                </tr>
                                            </table>

                                        </div>

                                    </div>


                                </div>

                                <div class="modal-footer">
                                    <input type="submit" id="update" name="update" class="btn  btn-primary  pull-left" data-dismiss="modal" value="Update">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                </div>

                            </form>
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
<script>
    $(document).ready(function () {
        $('#issue').nepaliDatePicker({
            npdMonth: true,
            npdYear: true

        });
    });

    $(document).ready(function () {
        $('#save').nepaliDatePicker({
            npdMonth: true,
            npdYear: true

        });
    });
    $(document).ready(function () {
        $('#dob').nepaliDatePicker({
            npdMonth: true,
            npdYear: true

        });
    });
    $('#claim').click(function () {
        $('#rno').text($('#remit_no').val());
        var remitCompany = $('#rcompany').find(":selected").text();
        $('#company').text(remitCompany);
        $('#name').text($('#rname').val());
        $('#fname').text($('#rfname').val());
        $('#addr').text($('#raddress').val());
        $('#dist').text($('#district').val());
        var sid = $('#sidtype').find(":selected").text();
        $('#idtype').text(sid);
        $('#issuedate').text($('#issue').val());
        $('#idnumber').text($('#idno').val());
        $('#dateofbirth').text($('#dob').val());
        $('#receivecontact').text($('#rcontact').val());
        $('#sendername').text($('#sname').val());
        $('#sendercontact').text($('#scontact').val());
        var srelation = $('#relation').find(":selected").text();
        $('#senderrelation').text(srelation);
        $('#sendercountry').text($('#country').val());
        $('#expectedamount').text($('#expamount').val());
        var name = $.trim($('#expamount').val());

        if (name === "") {
            alert("Please Fill Out All the details to make request");
            return false;

        }

        if (name > 1000000) {
            alert('You can only make request  upto 1000000');
            return false;

        }
        if (isNaN(name)) {
            location.reload();
            alert('please enter number in Amount Field');
            return false;

        }


    });



    $(document).ready(function () {
        $('#update').on('click', function () {
            var remitCompany = $('#rcompany').find(":selected").text();
            if ($("select option:selected").index() > 0) {
                $('#company').text(remitCompany);
            } else {

                alert("Please select remit type");
                $('.modal').fadeInSlow();
                return false;
            }

            var remit = $("#remit");
            var formData = new FormData(remit[0]);
            //var formData = JSON.stringify($("#remit").serializeArray());
            $.ajax({
                type: "POST",
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                url: "remitupdate.php?msg_id=<?php echo $msgid; ?>",
                data: formData,
                error: function (xhr, status) {
                    toastr.success(status);
                    console.log(xhr, status);
                },
                success: function (json) {
                    alert(json.message);
                    //toastr.success(json.message);
                    window.location = "remittance_detail.php";
                }
            });
        });
    });


</script>