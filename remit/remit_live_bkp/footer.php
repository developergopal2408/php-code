

<div class="footer" >
    Copyright © <a href="https://www.facebook.com/gopal.k.shah" target="_blank" style="color:#FFF;"><?php echo date('Y'); ?></a> JBS REMIT. All rights reserved.
</div>

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

        //$('#company').text($('#rcompany').val());
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
        $('#submit').on('click', function () {
            var remitCompany = $('#rcompany').find(":selected").text();
            if ($("select option:selected").index() > 0) {
                $('#company').text(remitCompany);
            } else {

                alert("Please select remit type");
                window.location = "index.php";
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
                url: "remitprocess.php",
                data: formData,
                error: function (xhr, status) {
                    alert(status);
                    console.log(xhr, status);
                },
                success: function (json) {
                    console.log(json.status);
                    alert(json.message);
                    
                    window.location = "remittanceDetail.php";
                }
            });
        });
    });


</script>
<script>

//table2excel.js
    ;
    (function ($, window, document, undefined) {
        var pluginName = "table2excel",
                defaults = {
                    exclude: ".noExl",
                    name: "Table2Excel"
                };
        // The actual plugin constructor
        function Plugin(element, options) {
            this.element = element;
            this.settings = $.extend({}, defaults, options);
            this._defaults = defaults;
            this._name = pluginName;
            this.init();
        }

        Plugin.prototype = {
            init: function () {
                var e = this;
                e.template = {
                    head: "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>",
                    sheet: {
                        head: "<x:ExcelWorksheet><x:Name>",
                        tail: "</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet>"
                    },
                    mid: "</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body>",
                    table: {
                        head: "<table>",
                        tail: "</table>"
                    },
                    foot: "</body></html>"
                };
                e.tableRows = [];
                // get contents of table except for exclude
                $(e.element).each(function (i, o) {
                    var tempRows = "";
                    $(o).find("tr").not(e.settings.exclude).each(function (i, o) {
                        tempRows += "<tr>" + $(o).html() + "</tr>";
                    });
                    e.tableRows.push(tempRows);
                });
                e.tableToExcel(e.tableRows, e.settings.name);
            },
            tableToExcel: function (table, name) {
                var e = this, fullTemplate = "", i, link, a;
                e.uri = "data:application/vnd.ms-excel;base64,";
                e.base64 = function (s) {
                    return window.btoa(unescape(encodeURIComponent(s)));
                };
                e.format = function (s, c) {
                    return s.replace(/{(\w+)}/g, function (m, p) {
                        return c[p];
                    });
                };
                e.ctx = {
                    worksheet: name || "Worksheet",
                    table: table
                };
                fullTemplate = e.template.head;
                if ($.isArray(table)) {
                    for (i in table) {
                        //fullTemplate += e.template.sheet.head + "{worksheet" + i + "}" + e.template.sheet.tail;
                        fullTemplate += e.template.sheet.head + "Table" + i + "" + e.template.sheet.tail;
                    }
                }

                fullTemplate += e.template.mid;
                if ($.isArray(table)) {
                    for (i in table) {
                        fullTemplate += e.template.table.head + "{table" + i + "}" + e.template.table.tail;
                    }
                }

                fullTemplate += e.template.foot;
                for (i in table) {
                    e.ctx["table" + i] = table[i];
                }
                delete e.ctx.table;
                if (typeof msie !== "undefined" && msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                    if (typeof Blob !== "undefined") {
                        //use blobs if we can
                        fullTemplate = [fullTemplate];
                        //convert to array
                        var blob1 = new Blob(fullTemplate, {type: "text/html"});
                        window.navigator.msSaveBlob(blob1, getFileName(e.settings));
                    } else {
                        //otherwise use the iframe and save
                        //requires a blank iframe on page called txtArea1
                        txtArea1.document.open("text/html", "replace");
                        txtArea1.document.write(e.format(fullTemplate, e.ctx));
                        txtArea1.document.close();
                        txtArea1.focus();
                        sa = txtArea1.document.execCommand("SaveAs", true, getFileName(e.settings));
                    }

                } else {
                    link = e.uri + e.base64(e.format(fullTemplate, e.ctx));
                    a = document.createElement("a");
                    a.download = getFileName(e.settings);
                    a.href = link;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }

                return true;
            }
        };
        function getFileName(settings) {
            return (settings.filename ? settings.filename : "table2excel") + ".xls";
        }

        $.fn[ pluginName ] = function (options) {
            var e = this;
            e.each(function () {
                if (!$.data(e, "plugin_" + pluginName)) {
                    $.data(e, "plugin_" + pluginName, new Plugin(this, options));
                }
            });
            // chain jQuery functions
            return e;
        };
    })(jQuery, window, document);
    /*$("#excel").click(function(){
     
     $("#customers").table2excel({
     exclude: ".noExl",
     name: "Excel Document customers"
     }); 
     
     });*/




</script>

<script>

    function load() {
        setTimeout(function () {
            $.ajax({
                url: "remitcount.php",
                cache: false,
                success: function (data) {
                    if (!$.trim(data)) {
                        toastr.hide();
                        return false;

                    } else {

                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": true,
                            "progressBar": false,
                            "positionClass": "toast-bottom-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "3000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        Command: toastr["error"](data)


                    }
                }

            });
        }, 1000);
    }





</script>

<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="js/nepali.datepicker.v2.2/nepali.datepicker.v2.2.min.js" type="text/javascript"></script>
<script src="js/toastr.js" type="text/javascript"></script>
</body>
</html>