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
                <small>Member Notification</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Member Notification</li>
            </ol>
        </section>
        <?php
        if (isset($_POST['submit'])) {
            $Subject = trim($_POST['subject']);
            $Message = trim($_POST['Message']);
            $query_string = "INSERT INTO MemberMessage(Subject,Message,PostedBy) VALUES('$Subject','$Message','$BranchID')";
            $insert = odbc_exec($connection, $query_string);
            if ($insert) {
                echo "<script>alert('Message Successfully Inserted..');window.location='message.php'</script>";
            } else {
                echo "<script>alert('Error Inserting Message To DB..');window.location='message.php'</script>";
            }
        }
        ?>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-4">
                        <div class="box box-solid ">
                            <div class="box-header with-border bg-red-active">
                                <span class="text-bold"><i class="fa fa-folder-o"></i> Add Notification</span>
                            </div>
                            <div class="box-body">
                                <!-- search form -->
                                <form name="goo" action="" method="post" class="form-horizontal" enctype="multipart/form-data" onsubmit="return ValidateForm()">
                                    <div class=" form-group">
                                        <div class="col-xs-12 form-group-sm">
                                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter Subject"><br/>
                                        </div>
                                        <div class="col-xs-12">
                                            <textarea name="Message" id="Message"  class="form-control required" placeholder="Enter Message"></textarea><br/><br/>
                                        </div>

                                        <div class="col-xs-12">
                                            <input type="submit" name="submit"   value="Submit" id="submit" class=" btn btn-sm bg-green pull-right">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-8">
                        <div class="box box-solid">
                            <div class="box-header with-border bg-red-active">
                                <span class="text-bold"><i class="fa fa-file-archive-o"></i> View Your Message</span>
                            </div>
                            <div class="box-body">
                                <table id="message" class="table table-bordered " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>MsgID</th>
                                            <th>Subject </th>
                                            <th>Message </th>
                                            <th>PostedBy </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "select Top 10 * from membermessage ORDER BY MsgID Desc";
                                        $run = odbc_exec($connection, $query);
                                        while ($row = odbc_fetch_array($run)) {
                                            $ro = odbc_exec($connection, "select * from OfficeDetail where ID='" . $row['PostedBy'] . "'");
                                            $res = odbc_fetch_array($ro);
                                            ?>
                                            <tr>
                                                <td><?php echo $row['MsgID']; ?></td>
                                                <td><?php echo $row['Subject']; ?></td>
                                                <td><?php echo $row['Message']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
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
    $("#message").DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'brtrip',
    });


    function ValidateForm() {
        if (document.goo.subject.value == '') {
            alert(" Please Input Some Text First ");
            return false;
        }
        if (document.goo.Message.value == '') {
            alert(" Please Input Some Message First ");
            return false;
        }
    }
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

// Load the Google Transliterate API
    google.load("elements", "1", {
        packages: "transliteration"
    });

    function onLoad() {
        var options = {
            sourceLanguage:
                    google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage:
                    [google.elements.transliteration.LanguageCode.NEPALI],
            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        };

        // Create an instance on TransliterationControl with the required
        // options.
        var control =
                new google.elements.transliteration.TransliterationControl(options);

        // Enable transliteration in the textbox with id
        // 'transliterateTextarea'.
        control.makeTransliteratable(['subject']);
        control.makeTransliteratable(['Message']);
    }
    google.setOnLoadCallback(onLoad);
</script>