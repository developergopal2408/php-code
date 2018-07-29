<?php
include_once 'top.php';
include_once 'header.php';
include_once 'db2.php';
$msg_id = $_GET['msg_id'];
//print_r($row);
?>
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-dashboard"></i> Remittance
                <small>View Detail</small>
            </h1>
            <ol class="breadcrumb">
                <?php
                $query = "SELECT * FROM remittance WHERE MSGID = '$msg_id' ";
                $sql = mysqli_query($conn, $query);
                $row = mysqli_fetch_array($sql);
                if ($branchid > 1 AND ($row['STATUS'] == 'PENDING' or  $row['STATUS'] == 'REJECTED')) {
                    ?>
                    <a href="edit_remit.php?edit=<?php echo $row['MSGID']; ?>" class="btn btn-sm bg-red pull-right"  >Edit</a>
                    <?php
                } else {
                    ?>
                    <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Remittance View Detail</li>
                    <?php
                }
                ?>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-solid">
                <div class="box-header">

                    <div class="row">
                        <div class="col-md-12" style="font-size: 12px;">
                            <div class="col-md-4">
                                <div class="col-xs-4"><label for="Remit No">Remit No: </label></div>
                                <div class="col-xs-8"><input class="form-control" id="rno" value="<?php echo $row['REMITNO']; ?>" readonly><br/></div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-xs-4"><label for="Remit Company">Remit Company: </label></div>
                                <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['REMITCOMPANY']; ?>" readonly><br/></div>
                            </div>
                            <div class="col-md-4">
                                <div class="col-xs-4"><label for="Expected Amount">Exp Amount: </label></div>
                                <div class="col-xs-8"><input class="form-control" id="expa"   value="<?php echo number_format($row['EXPECTEDAMT'],2); ?>" readonly><br/></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-body text-sm">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="col-md-4">
                                <h4 class="text-bold text-primary text-center">Receiver Detail</h4>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Receiver Name">Receiver Name: </label></div>
                                    <div class="col-xs-8"><input class="form-control" id="rname"  value="<?php echo $row['RECEIVERNAME']; ?>" readonly><br/></div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Receiver Father Name">R.Father Name: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['RECEIVERFATHERNAME']; ?>" readonly><br/></div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Address">Address: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['RECEIVERADDRESS']; ?>" readonly><br/></div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="City">City: </label></div>
                                    <div class="col-xs-8"><input class="form-control" id="rcity"  name="rcity" value="<?php echo $row['RECEIVERDISTRICT']; ?>" readonly><br/></div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="ID">ID TYPE: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['RECEIVERIDTYPE']; ?>" readonly><br/></div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="idno">ID No: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['RECEIVERIDNO']; ?>" readonly><br/></div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Contact no">Contact No: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['RECEIVERCONTACTNO']; ?>" readonly><br/></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-bold text-primary text-center">Sender Detail</h4>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Sender Name">Sender Name: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['SENDERNAME']; ?>" readonly><br/></div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Country">Country: </label></div>
                                    <div class="col-xs-8"><input class="form-control"   value="<?php echo $row['SENDERCOUNTRY']; ?>" readonly><br/></div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Contact No">Contact No: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['SENDERCONTACTNO']; ?>" readonly><br/></div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Relation">Relation</label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['SENDERRELATION']; ?>" readonly><br/></div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Contact no">Receiver DoB: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['RECEIVERDOB']; ?>" readonly><br/></div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <h4 class="text-bold text-primary text-center">Office Detail</h4>

                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Staff Name">Staff Name: </label></div>
                                    <div class="col-xs-8"><input class="form-control" value="<?php echo $row['STAFFNAME']; ?>" readonly><br/></div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Staff Code">Staff ID: </label></div>
                                    <div class="col-xs-8"><input class="form-control" value="<?php echo $row['STAFFID']; ?>" readonly><br/></div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Branch Code">Branch Code: </label></div>
                                    <div class="col-xs-8"><input class="form-control" value="<?php echo $row['BCODE']; ?>" readonly><br/></div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="Branch Name">Branch Name: </label></div>
                                    <div class="col-xs-8"><input class="form-control" value="<?php echo $row['BRANCHNAME']; ?>" readonly><br/></div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="date">Date: </label></div>
                                    <div class="col-xs-8"><input class="form-control" value="<?php echo $row['TDATE']; ?>" readonly ><br/></div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="sno">Serial No: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $row['SERIALNO']; ?>"  disabled><br/></div>
                                </div>
								<?php
                                $sqls = "SELECT * FROM staffmain WHERE StaffID='" . $row['STAFFID'] . "' ";
                                $res = odbc_exec($connection, $sqls);
                                $rows = odbc_fetch_array($res);
                                $mno = $rows['Mobile'];
                                ?>
                                <div class="col-xs-12">
                                    <div class="col-xs-4"><label for="sno">Staff Mobile: </label></div>
                                    <div class="col-xs-8"><input class="form-control"  value="<?php echo $mno; ?>"  disabled><br/></div>
                                </div>
								
                            </div>

                        </div>

                        <?php
                        if ($row['STATUS'] == 'PENDING') {
                            ?>

                            <div class="col-md-12">
                                <div class="col-xs-4">
                                    <a href="remittance_detail.php" class="btn btn-md btn-block btn-primary">Cancel</a>
                                </div>
                                <?php
                                if ($_SESSION['BranchID'] == '1') {
                                    ?>
                                    <div class="col-xs-4">
                                        <button type="button" id="reject"  class="btn btn-md btn-block btn-danger" style="margin-bottom:10px;" data-toggle="modal" data-target="#confirm-reject">Reject</button>
                                    </div>
                                    <div class="col-xs-4">
                                        <button type="button" id="claim"  class="btn btn-md btn-block btn-success" style="margin-bottom:10px;" data-toggle="modal" data-target="#confirm-approve">APPROVE</button>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                        } else {
                            ?>

                            <div class="col-md-12" style="font-size:12px;">
                                <div class="col-md-4">
                                    <div class="col-xs-4"><label for="Paid Amount">Paid Amount: </label></div>
                                    <div class="col-xs-8"><input  class="form-control" value="<?php
                                        if ($row['STATUS'] == 'APPROVED') {
                                            echo number_format($row['PAIDAMT'],2);
                                        } else {
                                            echo number_format($row['PAIDAMT'],2);
                                        }
                                        ?>"   disabled>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="col-xs-5">
                                        <label for="Remittance Reason"><?php
                                            if ($row['STATUS'] == 'REJECTED') {
                                                echo "<span class='text-danger'>Reason To Reject</span>";
                                            } else if ($row['STATUS'] == 'APPROVED') {
                                                echo "<span class='text-success'>Reason To APPROVE</span>";
                                            } else {
                                                echo 'PENDING';
                                            }
                                            ?></label></div>
                                    <div class="col-xs-7">
                                        <textarea cols="10" class="form-control text-primary" disabled><?php echo $row['REASON']; ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="col-xs-10">
                                        <a href="remittance_detail.php" class="btn btn-md btn-block btn-primary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>


                    </div>
                </div>
            </div>
            <!--Approve Modal-->
            <div class="modal fade" id="confirm-approve" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <form method="POST" action="" id="approveRemittance" name="approveRemittance" enctype="Application/form-data">
                            <div class="modal-body">
                                <h4 class="text-primary text-center">
                                    EXPECTED AMOUNT : <?php echo $row['EXPECTEDAMT']; ?>
                                </h4>
                                <div class="col-md-12" style="margin-top:30px;padding:20px;">
                                    <div class="col-xs-4"><label for="Paid Amount">Paid Amount: </label></div>
                                    <div class="col-xs-8"><input type="text" class="form-control" id="pamount"  name="pamount"><br/></div>
                                    <div class="col-xs-4"><label for="Reasong To Approve">Are You Sure You Want To Approve This Transaction? </label></div>
                                    <div class="col-xs-8"><textarea onClick="change();" class="form-control" cols="50" rows="10" name="approve" id="approve"   placeholder="You can write a reason to approve.."></textarea></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#"  id="approveRemit" class="btn  btn-primary  pull-left" data-dismiss="modal">Send</a>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--End of Approve Modal-->
            <!--Reject Modal Start-->
            <div class="modal fade" id="confirm-reject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <form method="POST" action="" id="cancelRemittance" name="cancelRemittance" enctype="Application/form-data">
                            <div class="modal-body">
                                <h4 class="text-primary text-center"></h4>
                                <div class="col-md-12" style="margin-top:30px;padding:20px;">
                                    <div class="col-xs-4" ><label for="Reject">Why You Want To Reject? </label></div>
                                    <div class="col-xs-6">
                                        <select id="rejecttype" name="rejecttype" class="form-control" required="Please Select The reason to reject">
                                            <option value="rtype">Select Reject  Type</option>
                                            <option value="Invalid Remittance Code">Invalid Remittance Code</option>
                                            <option value="Invalid Receiver Name">Invalid Receiver Name</option>
                                            <option value="Details Mismatch">Details Mismatch</option>
                                        </select>
                                        <br/>
                                    </div>
                                    <div class="col-xs-12">
                                        <label for="Reasong To Reject">Why You Want to Reject This Transaction? </label>
                                        <textarea onclick="details()" required class="form-control" cols="50" rows="10" name="reason" id="reason" placeholder="Please Provide the reason to reject this transaction of remittance..." ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#"  id="rejected" class="btn  btn-primary  pull-left" data-dismiss="modal">Send</a>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end of reject modal-->
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
    function change() {
        var exp = $.trim($('#expa').val());
        var amount = $.trim($('#pamount').val());
        if (amount > exp) {
            alert("Your are paying more than " + exp + " amount!\n\n i.e.  " + amount);
        }
        if (amount < exp) {
            alert("Your are paying less than " + exp + " amount!\n\n i.e.  " + amount);
        }
        var rname = $.trim($('#rname').val());
        var rno = $.trim($('#rno').val());
        var a = "You Can pay Rs. " + amount;
        var b = " to " + rname;
        var c = " having remit no " + rno;
        var textarea = a + b + c;
        document.getElementById("approve").value = textarea;
    }

    $(document).ready(function () {
        $('#approveRemit').on('click', function () {
            //change();
            var paidamt = $.trim($('#pamount').val());
            if (paidamt === '') {
                alert('Please Enter The Amount you want to pay before approve..');
                return false;
            }
            var approved = $.trim($('#approve').val());
            if (approved === '') {
                alert('Please Enter Reason to Approve this transaction of remittance..');
                return false;
            } else {

                var approveRemittance = $("#approveRemittance");
                var formData = new FormData(approveRemittance[0]);
                //var formData = JSON.stringify($("#approveRemittance").serializeArray());
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: "approveRemit.php?msg_id=<?php echo $msg_id; ?>",
                    data: formData,
                    error: function (xhr, status) {
                        alert(status);
                        console.log(xhr, status);
                    },
                    success: function (json) {
                        console.log(json.status);
                        toastr.success(json.message);
                        setTimeout(function () {
                            window.location.href = 'remittance_detail.php';
                        }, 3000);
                    }
                });

            }

        });

    });

</script>
<script>
    function details() {
        var remitreject = $('#rejecttype').find(":selected").text();
        var text = remitreject;

        document.getElementById("reason").value = text;
    }

    $(document).ready(function () {

        $('#rejected').on('click', function () {
            //details();

            var RejectType = document.getElementById("rejecttype");
            if (RejectType.options[RejectType.selectedIndex].value === 'rtype') {
                alert("Please select  Reject type");
                return false;
            }
            var rejecttype = $.trim($('#reason').val());
            if (rejecttype === '') {
                alert('Please Enter Reason to reject this transaction of remittance..');
                return false;
            } else {
                var cancelRemittance = $("#cancelRemittance");
                var formData = new FormData(cancelRemittance[0]);
                //var formData = JSON.stringify($("#cancelRemittance").serializeArray());
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: "cancelRemit.php?msg_id=<?php echo $msg_id; ?>",
                    data: formData,
                    error: function (xhr, status) {
                        alert(status);
                        console.log(xhr, status);
                    },
                    success: function (json) {
                        console.log(json.status);

                        toastr.success(json.message);
                        setTimeout(function () {
                            window.location.href = 'remittance_detail.php';
                        }, 3000);
                    }
                });

            }
        });

    });



</script> 