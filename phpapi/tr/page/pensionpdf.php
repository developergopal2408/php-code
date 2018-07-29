<?php
include_once 'top.php';
include_once 'header.php';
$mid = $_GET['mid'];
$oid = $_GET['officeid'];
?>

<!-- Site wrapper -->
<div class="wrapper">

    <?php
    include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
    include_once 'sidebar.php'; //Include Sidebar.php-->
    ?>
    <style>
       .body{margin: 0pt;}
    </style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <?php
            $sql = "SELECT * FROM OfficeDetail WHERE ID='$oid' ";
            $reso = sqlsrv_query($connection, $sql);
            $row = sqlsrv_fetch_array($reso);
            $branchName = $row['Name'];
            ?>
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>
                <small>Pension Card</small>
            </h1>
            <ol class="breadcrumb">
                <a  href="javascript:window.print()" ><i class="glyphicon glyphicon-print"></i></a>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-header" style="margin-bottom:1px;">
                            <div class="col-md-12">
                                <div class="col-xs-3 pull-left">
                                    <img class="img-circle " src="../logo.png" style="padding: 5px;">
                                </div>
                                <div class="col-xs-6">
                                    <h4 class="text-center text-uppercase text-bold" style="font-size:15px;">Jeevan Bikas Samaj
                                        <br/><?php echo "<span class='text-sm'>".$branchName."</span>";?><br/>
                                        <span class="text-sm">Pension Card</span>
                                    </h4>
                                </div>
                                <div class="pull-right box-tools">
                                    <img class="img-rounded" src="" >
                                </div>
                            </div>
                        </div>
                        <div class="box-body" >
                            <div class="col-md-12" style="border:1px solid black;">
                                <table class="table no-border">
                                    <?php
                                    $qry = "select m.membercode,m.firstname+' '+m.Lastname as MemberName,m.DOB,(select centername from centermain where centerid=m.centerid and officeid=m.officeid)centername,m.spouseFather,m.Fatherinlaw,v.vdcname+' '+m.wardno as Address ,m.regdate,m.photo
                                            from member m, vdc v,officedetail o
                                            where m.vdcid=v.vdcid
                                            and m.officeid='$oid' and m.memberid='$mid' and m.status='active'
                                            group by m.membercode,m.firstname,m.Lastname ,m.DOB,m.spouseFather,m.Fatherinlaw,m.wardno ,m.regdate,m.photo,m.centerid,m.officeid,v.vdcname
                                            order by m.membercode";
                                    $result = sqlsrv_query($connection, $qry);
                                    $res = sqlsrv_fetch_array($result);
                                    ?>
                                    <tr class="text-bold  text-sm" >
                                        <td>Code: <?php echo $res['membercode']; ?></td>
                                        <td>Name: <?php echo $res['MemberName']; ?></td>
                                        <td>DOB: <?php echo $res['DOB']; ?></td>
                                        <td><img class="img-rounded pull-right" src="../photo/<?php echo $res['photo']; ?>" width="50" height="40"></td>
                                    </tr>
                                    <tr class="text-bold  text-sm">
                                        <td>Center Name: <?php echo $res['centername']; ?></td>                                                                           
                                        <td>Spouse Name: <?php echo $res['spouseFather']; ?></td>
                                        <td>Father In Law: <?php echo $res['Fatherinlaw']; ?></td>
                                    </tr>
                                    <tr class="text-bold  text-sm">
                                        <td>Address: <?php echo $res['Address']; ?></td>
                                        <td>Reg Date: <?php echo $res['regdate']; ?></td>
                                    </tr>
                                </table>
                            </div><!--end of col-md-12-->

                            <div class="col-md-12 text-sm" style="border:1px solid black;">
                                <div class="col-sm-4 pull-left">
                                    <table class="table no-border text-bold padding-0">
                                        <tr>
                                            <td style="height:60px;">Finger Right</td>
                                            <td style="height:60px;">Finger Left</td>
                                        </tr> 
                                        <tr>
                                            <td>Signature:</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-8 pull-right" style="padding-bottom: 20px;">

                                    <table  class="table table-condensed table-bordered" >
                                        <tr class="text-sm">
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Year</th>
                                            <th>Nominee/Relation</th>
                                            <th>StaffCode</th>
                                            <th>Signature</th>
                                        </tr>
                                        <?php
                                        $qry = "select memberid,startdate,amount,period,Nominee
                                                from savingaccount
                                                where memberid = '$mid' and officeid = '$oid'  and isactive='Y' and savingtypeid=5
                                                order by startdate DESC";
                                        $result = sqlsrv_query($connection, $qry);
                                        while ($res = sqlsrv_fetch_array($result)) {
                                            ?>
                                        <tr class="text-sm">
                                                <td><?php echo $res['startdate']; ?></td>
                                                <td><?php echo $res['amount']; ?></td>
                                                <td><?php echo $res['period']; ?></td>
                                                <td><?php echo $res['Nominee']; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                                <br/><br/><br/><br/><br/><br/>
                            </div>
                            <div class="col-md-12 text-sm " style="border: 1px solid black;">
                                <table class="table no-border ">
                                    <tr class="text-left">Approved By</tr>
                                    <tr>
                                        <td>Name:</td>
                                        <td>Designation:</td>
                                    </tr>
                                    <tr>
                                        <td>Date:</td>
                                        <td>Signature:</td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- end of box-body-->

                        <div class="box-header" style="margin-bottom:1px;">
                            <div class="col-md-12">
                                <div class="col-xs-3 pull-left">
                                    <img class="img-circle " src="../logo.png" style="padding: 5px;">
                                </div>
                                <div class="col-xs-6">
                                    <h4 class="text-center text-uppercase text-bold" style="font-size:15px;">Jeevan Bikas Samaj
                                        <br/><?php echo "<span class='text-sm'>".$branchName."</span>";?><br/>
                                        <span class="text-sm">Pension Card</span>
                                    </h4>
                                </div>
                                <div class="pull-right box-tools">
                                    <img class="img-rounded" src="" >
                                </div>
                            </div>
                        </div>
                        <div class="box-body" >
                            <div class="col-md-12" style="border:1px solid black;">

                                <?php
                                $qry = "select m.membercode,m.firstname+' '+m.Lastname as MemberName,m.DOB,(select centername from centermain where centerid=m.centerid and officeid=m.officeid)centername,m.spouseFather,m.Fatherinlaw,v.vdcname+' '+m.wardno as Address ,m.regdate,m.photo
                                            from member m, vdc v,officedetail o
                                            where m.vdcid=v.vdcid
                                            and m.officeid='$oid' and m.memberid='$mid' and m.status='active'
                                            group by m.membercode,m.firstname,m.Lastname ,m.DOB,m.spouseFather,m.Fatherinlaw,m.wardno ,m.regdate,m.photo,m.centerid,m.officeid,v.vdcname
                                            order by m.membercode";
                                $result = sqlsrv_query($connection, $qry);
                                $res = sqlsrv_fetch_array($result);
                                ?>
                                <table class="table no-border text-sm">
                                    <tr class="text-bold">
                                        <td>Code: <?php echo $res['membercode']; ?></td>
                                        <td>Name: <?php echo $res['MemberName']; ?></td>
                                        <td>DOB: <?php echo $res['DOB']; ?></td>
                                        <td><img class="img-rounded pull-right" src="../photo/<?php echo $res['photo'];?>" width="50" height="40"></td>
                                    </tr>
                                    <tr class="text-bold">
                                        <td>Center Name: <?php echo $res['centername']; ?></td>                                                                           
                                        <td>Spouse Name: <?php echo $res['spouseFather']; ?></td>
                                        <td>Father In Law: <?php echo $res['Fatherinlaw']; ?></td>
                                    </tr>
                                    <tr class="text-bold">
                                        <td>Address: <?php echo $res['Address']; ?></td>
                                        <td>Reg Date: <?php echo $res['regdate']; ?></td>
                                    </tr>
                                </table>
                            </div><!--end of col-md-12-->

                            <div class="col-md-12 text-sm" style="border:1px solid black;">
                                <div class="col-sm-4 pull-left">
                                    <table class="table no-border text-bold padding-0">
                                        <tr>
                                            <td style="height:60px;">Finger Right</td>
                                            <td style="height:60px;">Finger Left</td>
                                        </tr> 
                                        <tr>
                                            <td>Signature:</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-8 pull-right text-sm">
                                    <table class="table table-condensed table-bordered">
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Year</th>
                                            <th>Nominee/Relation</th>
                                            <th>StaffCode</th>
                                            <th>Signature</th>
                                        </tr>
                                        <?php
                                        $qry = "select memberid,startdate,amount,period,Nominee
                                                from savingaccount
                                                where memberid = '$mid' and officeid = '$oid'  and isactive='Y' and savingtypeid=5
                                                order by startdate DESC";
                                        $result = sqlsrv_query($connection, $qry);
                                        while ($res = sqlsrv_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $res['startdate']; ?></td>
                                                <td><?php echo $res['amount']; ?></td>
                                                <td><?php echo $res['period']; ?></td>
                                                <td><?php echo $res['Nominee']; ?></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                                <br/><br/><br/><br/><br/><br/>
                            </div>
                            <div class="col-md-12 text-sm" style="border: 1px solid black;">
                                <table class="table no-border ">
                                    <tr class="text-left">Approved By</tr>
                                    <tr>
                                        <td>Name:</td>
                                        <td>Designation:</td>
                                    </tr>
                                    <tr>
                                        <td>Date:</td>
                                        <td>Signature:</td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- end of box-body-->

                    </div><!-- end of box-->

                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
    <?php
//include_once 'copyright.php';
    ?>
</div>
<!-- ./wrapper -->

<?php
include_once 'footer.php';
?>
	

