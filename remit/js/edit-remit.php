<?php
include_once 'header.php';
if (!isset($_SESSION['STAFFID'])) {
    header('Location:login.php');
}
$staffid = $_SESSION['STAFFID'];
$branch_name = $_SESSION['BRANCHNAME'];
$branch_code = $_SESSION['BRANCHCODE'];
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$uname = "$fname $lname";
include_once 'nav.php';
$sql = mysqli_query($con, "SELECT * FROM id_type");
$rcompany = mysqli_query($con, "SELECT * FROM remit_company");
$relation = mysqli_query($con, "SELECT * FROM relation");

//$serial = rand(1, 1000);

$msgid = $_GET['edit'];
$query = "SELECT * FROM remittance WHERE MSGID = '$msgid'";
$run = mysqli_query($con, $query);
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
<body style="padding-top: 4rem;">
    <div class="container-fluid" >
        <div class="row" >
            <form  name="remits" id="remits" method="post"   enctype="application/form-data">
                <div class="col-md-12">
                    <div class="col-xs-offset-4 col-xs-4  " >
                        <h2 class="text-center" style="padding:3px;background:#286090;color:#fff;border-radius: 5px;">Remittance Payment</h2>
                        <hr>
                    </div>
                    <div class="col-xs-4">
                        <span class="text-danger">
                            <?php
                            if (isset($error)) {
                                echo $error;
                            }
                            ?>
                        </span>
                    </div>
                    <div class="col-md-6" >
                        <input type="hidden" class="form-control" id="serial"  name="serial" value="<?php echo $serialno; ?>">
                       <!-- <div class="col-xs-1">
                            <h5 class="vericaltext pull-left" >RECEIVER DETAILS</h5>
                        </div>-->
                       <div class="col-xs-11" style="font-size: 12px;">
                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Remit No">Remit No: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="16" class="form-control" id="remit_no"  name="remit_no" value="<?php echo $remitno;?>"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Remit Company">Remittance Company: </label></div>
                                <div class="col-xs-6">
                                    <select id="rcompany" name="rcompany" class="form-control" required="Please Select Remittance Company">
                                        <option value="<?php echo $remit_co;?>"  ><?php echo $remit_co;?></option>
                                        <?php
                                        foreach ($rcompany as $rco) {
                                            echo '<option value="' . $rco['REMITCOMPANY'] . '" >' . $rco['REMITCOMPANY'] . '</option>';
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Receiver Name">Receiver Name: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="rname"  name="rname" value="<?php echo $rname;?>"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Receiver's Father Name">Receiver's Father Name: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="rfname"  name="rfname" value="<?php echo $rfname;?>"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Address">Address: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="raddress"  name="raddress" value="<?php echo $raddress;?>"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Disctrict">District: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="district"  name="district" value="<?php echo $rdistrict;?>"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="ID">ID Type: </label></div>
                                <div class="col-xs-6">

                                    <select id="sidtype" name="sidtype" class="form-control" required="Please Select ID TYPE">
                                        <option value="<?php echo $idtype;?>"><?php echo $idtype;?></option>
                                        <?php
                                        foreach ($sql as $sid) {
                                            echo '<option value="' . $sid['id_name'] . '">' . $sid['id_name'] . '</option>';
                                        }
                                        ?>
                                    </select>

                                </div>

                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="ID Issue NO">Issue Date: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="10" class="form-control" id="issue"  name="issue" value="<?php echo $rissue;?>"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="ID NO">ID No: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="10" class="form-control" id="idno"  name="idno" value="<?php echo $ridno;?>"></div>
                            </div>


                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="DOB">Date of Birth: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="10" class="form-control" id="dob"  name="dob" value="<?php echo $rdob;?>"></div>
                            </div>



                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Contact No">Receiver Contact No: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="10" class="form-control" id="rcontact"  name="rcontact" value="<?php echo $rcontact;?>"></div>
                            </div>

                        </div>

                    </div>

                    <div class="col-md-6" >
                        <!--<div class="col-xs-1">
                            <h5 class="vericaltext pull-left" >SENDER DETAILS</h5>
                        </div>-->

                        <div class="col-xs-11" style="font-size: 12px;">
                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Sender Name">Sender's Name: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="sname"  name="sname" value="<?php echo $sname;?>"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Sender Contact">Sender's Contact No: </label></div>
                                <div class="col-xs-6"><input maxlength="10" type="text" class="form-control" id="scontact"  name="scontact" value="<?php echo $scontact;?>"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Sender Relation">Relation: </label></div>
                                <div class="col-xs-6">

                                    <select id="relation" name="relation" class="form-control" required="Please Select Relation Type">
                                        <option value="<?php echo $srelation;?>"><?php echo $srelation;?></option>
                                        <?php
                                        foreach ($relation as $relat) {
                                            echo '<option value="' . $relat['relation'] . '">' . $relat['relation'] . '</option>';
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Sender Country">Country: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="country"  name="country" value="<?php echo $scountry;?>"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Expected Amount">Expected Amount: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="7" class="form-control" id="expamount"  name="expamount" value="<?php echo $expamt;?>"></div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12" style="margin-top:10px;">
                        <div class="col-xs-6 pull-right">
                            <button type="button" id="claim"   class="btn btn-md btn-block btn-primary" style="margin-bottom:10px;" data-toggle="modal" data-target="#confirm-submit">Update</button>
                        </div>
                        <div class="col-xs-6 pull-left">
                            <button type="reset" id="reset" name="reset" class="btn btn-md btn-block btn-danger" style="margin-bottom:10px;">Reset</button>
                        </div>
                    </div>
                </div><!--end of col-md-12-->
            </form>
        </div>


        <div class="row">
            <div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-body">
                            <h4 class="text-primary">Are you sure you want to submit the following details?</h4>
                            <div class="col-md-12" style="margin-top:30px;padding:20px;">
                                <div class="col-xs-6">
                                    <!-- We display the details entered by the user here -->
                                    <table class="table table-striped responsive-table table-bordered">

                                        <tr>
                                            <th>Remit No</th>
                                            <td id="rno"></td>
                                        </tr>
                                        <tr>
                                            <th>Remit Company</th>
                                            <td id="company"></td>
                                        </tr>
                                        <tr>
                                            <th>Receiver Name</th>
                                            <td id="name"></td>
                                        </tr>
                                        <tr>
                                            <th>Receiver Father Name</th>
                                            <td id="fname"></td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td id="addr"></td>
                                        </tr>
                                        <tr>
                                            <th> District</th>
                                            <td id="dist"></td>
                                        </tr>

                                        <tr>
                                            <th>ID Type</th>
                                            <td id="idtype"></td>
                                        </tr>

                                        <tr>
                                            <th>Issue Date</th>
                                            <td id="issuedate"></td>
                                        </tr>
                                        <tr>
                                            <th>ID No</th>
                                            <td id="idnumber"></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth</th>
                                            <td id="dateofbirth"></td>
                                        </tr>
                                        <tr>
                                            <th>Receiver Contact No</th>
                                            <td id="receivecontact"></td>
                                        </tr>

                                    </table>

                                </div>
                                <div class="col-xs-6">
                                    <table class="table table-striped responsive-table table-bordered">
                                        <tr>
                                            <th>Sender Name</th>
                                            <td id="sendername"></td>
                                        </tr>
                                        <tr>
                                            <th>Sender Contact No</th>
                                            <td id="sendercontact"></td>
                                        </tr>
                                        <tr>
                                            <th>Relation</th>
                                            <td id="senderrelation"></td>
                                        </tr>
                                        <tr>
                                            <th>Country</th>
                                            <td id="sendercountry"></td>
                                        </tr>
                                        <tr>
                                            <th>Expected Amount</th>
                                            <td id="expectedamount"></td>
                                        </tr>
                                    </table>

                                </div>

                            </div>


                        </div>

                        <div class="modal-footer">
                            <a href="#" id="update" class="btn  btn-primary  pull-left" data-dismiss="modal">Send</a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>

    <script>
        $(document).ready(function () {
            $('#update').on('click', function () {


                var remits = $("#remits");
                var formData = new FormData(remits[0]);

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
                        alert(status);
                        console.log(xhr, status);
                    },
                    success: function (json) {
                        //console.log(json.status);

                        alert(json.message);
                        toastr.success('submitted Successfully');
                        window.location = "remittanceDetail.php";
                    }
                });
            });
        });


    </script>


    <?php
    include 'footer.php';
    ?>
    
