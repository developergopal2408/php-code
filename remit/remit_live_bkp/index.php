<?php
include_once 'header.php';

if (!isset($_SESSION['STAFFID'])) {
    header('Location:login.php');
}
?>
<style>
    label,input{
        padding-top:15px;
    }
</style>
<?php
$staffid = $_SESSION['STAFFID'];
$query = mysqli_query($con, "SELECT * FROM users us LEFT JOIN staffmain s ON(s.STAFFID = us.STAFFID) WHERE us.STAFFID = '$staffid'");
$row = mysqli_fetch_array($query);
$_SESSION['firstname'] = $row['FIRSTNAME'];
$_SESSION['lastname'] = $row['LASTNAME'];
$_SESSION['BRANCHNAME'] = $row['BRANCHNAME'];
$_SESSION['BRANCHID'] = $row['BRANCHID'];
$_SESSION['BRANCHCODE'] = $row['BRANCHCODE'];
$_SESSION['STAFFCODE'] = $row['STAFFCODE'];
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$uname = "$fname $lname";
$_SESSION['CATEGORYID'] = $row['CATEGORYID'];
$_SESSION['POSITION'] = $row['POSITION'];
$_SESSION['IS_APPROVED_BY'] = $row['IS_APPROVED_BY'];
$_SESSION['IS_CHECKED_BY'] = $row['IS_CHECKED_BY'];
$_SESSION['JOBTYPEID'] = $row['JOBTYPEID'];
$sql = mysqli_query($con, "SELECT * FROM id_type");
$rcompany = mysqli_query($con, "SELECT * FROM remit_company");
$relation = mysqli_query($con, "SELECT * FROM relation");
$serial = rand(1, 1000);
include_once 'nav.php';
?>

<body style="padding-top: 4rem;" onload="load();">
    <div class="container-fluid" >
        <div class="row" >
            <form  name="remit" id="remit" method="post"   enctype="application/form-data">
                <div class="col-md-12">
                    <div class="col-xs-offset-4 col-xs-4  " >
                        <h5 class="btn btn-flat btn-primary " style="margin-top:30px;margin-left: 100px;font-weight:bold;">Remittance Payment</h5>
                        <hr>
                    </div>
                    <div class="col-md-6" >
                        <input type="hidden" class="form-control" id="serial"  name="serial" value="<?php echo $serial; ?>">
                        <div class="col-xs-11" style="font-size: 12px;">
                            <div class="col-xs-12" >
                                <div class="col-xs-4"><label for="Remit No">Remit No: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="16" class="form-control" id="remit_no"  name="remit_no" ></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Remit Company">Remittance Company: </label></div>
                                <div class="col-xs-6">
                                    <select id="rcompany" name="rcompany" class="form-control" required="Please Select Remittance Company">
                                        <option value="selectremit">Select Remittance Company</option>
                                        <?php
                                        foreach ($rcompany as $rco) {
                                            echo '<option value="' . $rco['REMITCOMPANY'] . '">' . $rco['REMITCOMPANY'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Receiver Name">Receiver Name: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="rname"  name="rname" ></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Receiver's Father Name">Receiver's Father Name: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="rfname"  name="rfname" ></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Address">Address: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="raddress"  name="raddress" ></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Disctrict">District: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="district"  name="district" ></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="ID">ID Type: </label></div>
                                <div class="col-xs-6">
                                    <select id="sidtype" name="sidtype" class="form-control" required="Please Select ID TYPE">
                                        <option value="">Select ID Type</option>
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
                                <div class="col-xs-6"><input type="text" maxlength="10" class="form-control" id="issue"  name="issue" ></div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="ID NO">ID No: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="15" class="form-control" id="idno"  name="idno" ></div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="DOB">Date of Birth: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="10" class="form-control" id="dob"  name="dob" ></div>
                            </div>
                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Contact No">Receiver Contact No: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="10" class="form-control" id="rcontact"  name="rcontact" ></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" >
                        <div class="col-xs-11" style="font-size: 12px;">
                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Sender Name">Sender's Name: </label></div>
                                <div class="col-xs-6"><input type="text" class="form-control" id="sname"  name="sname" ></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Sender Contact">Sender's Contact No: </label></div>
                                <div class="col-xs-6"><input maxlength="10" type="text" class="form-control" id="scontact"  name="scontact" ></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Sender Relation">Relation: </label></div>
                                <div class="col-xs-6">

                                    <select id="relation" name="relation" class="form-control" required="Please Select Relation Type">
                                        <option value="">Select Relation</option>
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
                                <div class="col-xs-6"><input type="text" class="form-control" id="country"  name="country"></div>
                            </div>

                            <div class="col-xs-12">
                                <div class="col-xs-4"><label for="Expected Amount">Expected Amount: </label></div>
                                <div class="col-xs-6"><input type="text" maxlength="7" class="form-control" id="expamount"  name="expamount" ></div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12" style="margin-top:7px;">
                        <div class="col-xs-6 pull-right">
                            <button type="button" id="claim"  class="btn btn-md btn-block btn-primary" style="margin-bottom:10px;" data-toggle="modal" data-target="#confirm-submit">Submit</button>
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
                            <a href="#" id="submit" class="btn  btn-primary  pull-left" data-dismiss="modal">Send</a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>




    <?php
    include 'footer.php';
    ?>
    
