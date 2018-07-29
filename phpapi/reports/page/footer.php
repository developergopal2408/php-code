<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../nepali.datepicker.v2.2/nepali.datepicker.v2.2.min.js"></script>
<script src="../plugins/select2/select2.full.min.js"></script>
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="../plugins/fastclick/fastclick.min.js"></script>
<script src="../dist/js/app.min.js"></script>
<script src="../js/tableExport.js" type="text/javascript"></script>
<script src="../js/jquery.base64.js" type="text/javascript"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
   /* $(document).ready(function () {
        $("#excel").click(function (e) {
            //getting values of current time for generating the file name
            var dt = new Date();
            var day = dt.getDate();
            var month = dt.getMonth() + 1;
            var year = dt.getFullYear();
            var hour = dt.getHours();
            var mins = dt.getMinutes();
            var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
            //creating a temporary HTML link element (they support setting file names)
            var a = document.createElement('a');
            //getting data from our div that contains the HTML table
            var data_type = 'data:application/vnd.ms-excel';
            var table_div = document.getElementById('vd');
            var table_html = table_div.outerHTML.replace(/ /g, '%20');
            a.href = data_type + ', ' + table_html;
            //setting the file name
            a.download = 'StaffList_' + postfix + '.xls';
            //triggering the function
            a.click();
            //just in case, prevent default behaviour
            e.preventDefault();
        });
    });*/

    $('tr[data-href]').on("click", function () {
        document.location = $(this).data('href');
    });

    $('#id').change(function () {
        document.getElementById("id");
        //alert("You selected " + doc.options[doc.selectedIndex].value);
    });



</script>

<script>
    $(document).ready(function () {
        $('#myTable1').dataTable({
            order: [0, 'ASC']
        });

        $('#trial').dataTable({  
            "scrollY": "300px",
            "scrollCollapse": true,
            "paging": false,
        });
		
		$('#pension').dataTable({
            "scrollY": "300px",
            "scrollCollapse": true,
            "paging": false,
            "dom": '<"toolbar">frtip',
        });

        $("#voucher").DataTable({
            "order": [[1, "asc"]],
            //"pagingType": "full",
            "scrollY": "300px",
            "scrollCollapse": true,
            "paging": false,
            "dom": '<"toolbar">frtip',
        });

        $("#daybook").DataTable({
            //"scrollY": "300px",
            "scrollX": true,
            "scrollCollapse": true,
            "paging": true,
        });
		
		$("#daybook2").DataTable({
            "scrollY": "300px",
            "scrollX": true,
            "scrollCollapse": true,
            "paging": false,
        });


        $("#vouchers").DataTable({
            "order": [[2, "asc"]],
            //"pagingType": "full",
            "scrollY": "300px",
            "scrollCollapse": true,
            "paging": false,
            "dom": '<"toolbar">frtip',
        });
		
		$("#loanapproval").DataTable({
            "scrollX": true,
			"scrollY": "300px",
            "scrollCollapse": true,
            "paging": false,
            "dom": '<"toolbar">frtip',
        });

        //Initialize Select2 Elements
        $(".select2").select2();

        //

        $('#date1').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
        });

        $('#date2').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
        });

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




