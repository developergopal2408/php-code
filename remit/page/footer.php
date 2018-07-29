<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../nepali.datepicker.v2.2/nepali.datepicker.v2.2.min.js" type="text/javascript"></script>
<script src="../plugins/select2/select2.full.min.js"></script>
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="../plugins/fastclick/fastclick.min.js"></script>
<script src="../bootstrap/js/toastr.js" type="text/javascript"></script>
<script src="../dist/js/app.min.js"></script>
<!--<script src="../plugins/dt/jquery.dataTables.min.js" type="text/javascript"></script>-->
<script src="../plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="../plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="../plugins/dt/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="../plugins/dt/vfs_fonts.js" type="text/javascript"></script>
<script src="../plugins/dt/buttons.flash.min.js" type="text/javascript"></script>
<script src="../plugins/dt/buttons.html5.min.js" type="text/javascript"></script>
<script src="../plugins/dt/pdfmake.min.js" type="text/javascript"></script>
<script src="../plugins/dt/jszip.min.js" type="text/javascript"></script>
<script src="../plugins/dt/buttons.print.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        $('#fdate').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
        });
        $('#tdate').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
        });



    });
    $('tr[data-href]').on("click", function () {
        document.location = $(this).data('href');
    });
</script>

<script>
    var AdminLTEOptions = {
        //Enable sidebar expand on hover effect for sidebar mini
        //This option is forced to true if both the fixed layout and sidebar mini
        //are used together
        sidebarExpandOnHover: true,
        //BoxRefresh Plugin
        enableBoxRefresh: true,
        //Bootstrap.js tooltip
        enableBSToppltip: true
    };
    /** add active class and stay opened when selected */
    var url = window.location;

// for sidebar menu entirely but not cover treeview
    $('ul.sidebar-menu a').filter(function () {
        return this.href == url;
    }).parent().addClass('active');

// for treeview
    $('ul.treeview-menu a').filter(function () {
        return this.href == url;
    }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');

</script>




</body>
</html>




