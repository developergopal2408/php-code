<?php
include_once 'top.php';
include_once 'header.php';
?>
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-money"></i> 
                <small>Fund Due</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Fund Due</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">

                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" 
                                                   value="<?php
                                                   if (isset($_POST['date1'])) {
                                                       echo $_POST['date1'];
                                                   } else {
                                                       echo $cdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>
                                        <?php
                                        if ($BranchID == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="oid" id="oid" class="form-control select2" >
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                    $result = odbc_exec($connection, $sql1);

                                                    while ($rows = odbc_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['ID']; ?>" <?php if($rows['ID'] == $_POST['oid']){echo "selected";} ?>><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="pull-right">
                                    <a href="funddue.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="box-body"> 
                            <?php
                            if (isset($_POST['search'])) {
                                $sqls = "SELECT Name FROM OfficeDetail Where ID = '" . $_POST['oid'] . "' ";
                                $results = odbc_exec($connection, $sqls);
                                $reso = odbc_fetch_array($results);
                                echo "<h5 class='text-center text-bold'>Fund OverDue - " . $reso['Name'] . "( " . $_POST['date1'] . "  )</h5>";
                            }
                            ?>
                            <table id="funddue" class="display" > 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Name</th>
                                        <th>MemberID</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>InsuaranceType</th>
                                        <th>SaveDate</th>
                                        <th>PreBal</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $id = $_POST['oid'];
                                    $date1 = $_POST['date1'];
                                    if ($_SESSION['BranchID'] == 1 AND $id == "") {
                                        $idx = "";
                                    } else if ($_SESSION['BranchID'] == 1 AND $id != 1) {
                                        $idx = "and a.officeid = '$id'";
                                    } else {
                                        $idx = "and a.officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    $total = 0.0;
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.memberid,m.memberid, m.membercode, m.firstname+' '+m.LastName as MemberName, i.insurancetype, a.savedate, 
                                                sum(a.prebal)prebal,(select name from officedetail where ID = m.officeid)name
                                                from member m, insurancedetail a, insurancetype i
                                                where m.officeid=a.officeid and m.memberid=a.memberid  and i.insurancetypeid=a.insurancetypeid  
                                                and a.savedate = '$date1'  $idx
                                                group by m.memberid, m.membercode,m.firstname,m.lastname,a.savedate, i.insurancetype,m.officeid
                                                having sum(a.prebal)>0 order by a.savedate,m.membercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $total += $res['prebal'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['name']; ?></td>
                                                <td><?php echo $res['memberid']; ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['insurancetype']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['prebal']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>

                                    <tr class="bg-red text-bold">
                                        <td colspan="6">Total</td>
                                        <td><?php echo $total; ?></td>
                                    </tr>

                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!--/.content -->
    </div>
    <!--/.content-wrapper -->
    <?php
    include_once 'copyright.php';
    ?>
</div>
<!-- ./wrapper -->
<?php
include_once 'footer.php';
?>
<script>
    /* $(document).ready(function() {
     $('#funddue').DataTable();
     } );*/
    $('#funddue').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Fund Due Detail - ' + $("#date1").val(),
            },
            {
                extend: 'print',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Fund Due Detail - ' + $("#date1").val(),
                customize: function (win) {
                    $(win.document.body)
                            .css({
                                'font-size': '10pt',
                                'text-align': 'center'
                            })
                            .prepend(
                                    '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                                    );
                    $(win.document.body).find('table')
                            .addClass('display')
                            .css({
                                'padding': '5pt',
                                'font-size': '10pt',
                                'margin': '1px'
                            });
                }

            }
        ]
    });


</script>

