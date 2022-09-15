<?php
session_start();
$user_data = [];
include 'data_manipulator_config.php';
$link = config();
$sql = "SELECT id,course_name from course ORDER BY id";
$result = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($result, $user_data);
    }
} else {
    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
}
$data = [];

if (isset($_GET['course_group']) && isset($_GET['date_from']) && isset($_GET['date_to'])) {
    $link = config();
    $st_date = date('Y-m-d H:i:s', strtotime($_GET['date_from']) + 1);
    $end_date = date('Y-m-d H:i:s', strtotime($_GET['date_to']) + ((60 * 60 * 24) - 1));

    $sql = "SELECT ad.*,
        cr.course_name, 
        cr.fees_breakup, 
        cr.fees as course_total_fees,
        inv.id as invoice_number, 
        inv.invoice_no, 
        inv.data as invoice_data, 
        inv.created_by as invoice_by,
        inv.created_date as invoice_date 
        from admission_form ad 
        JOIN invoices inv on inv.id = ad.invoice_id 
        JOIN course cr on cr.id = ad.class 
        WHERE ad.class IN (SELECT id from course WHERE course_group='" . $_GET['course_group'] . "')" .
//        AND inv.created_by = '" . $_SESSION['user'] . "' 
            " AND ad.created_date between '" . $st_date . "' and '" . $end_date . "'";


    if ($res = mysqli_query($link, $sql)) {
        while ($course_data = mysqli_fetch_assoc($res)) {
            array_push($data, $course_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }
}
$headers = [];
$fees_arr;
$invoice_arr;
$tutuion_fees = [];
for ($index = 0; $index < count($data); $index++) {
    if (!empty($data[$index])) {
        $fees_arr = (json_decode($data[$index]['fees_breakup']));
        $invoice_arr = (json_decode($data[$index]['invoice_data']));
        for ($i = 0; $i < count($fees_arr); $i++) {
            if (($fees_arr[$i]->header == 'TUITION FEE') || (property_exists($invoice_arr, 'TUITION_FEE'))) {
                array_push($tutuion_fees, $fees_arr[$i]->value);
            } else {
                
            }
            array_push($headers, $fees_arr[$i]->header);
        }
    }
}
sort($headers);
$headers = (array_values(array_unique($headers)));

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
            <?php include 'headTag.html'; ?>
    <body class="no-skin">
        <?php include 'navbar.html'; ?>
        <div class="main-container " id="main-container">
            <?php include 'test.php'; ?>
            <div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs breadcrumbs-fixed  invisible-row" id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <a href="#">Home</a>
                            </li>

                            <li>
                                <a href="#">Reports</a>
                            </li>
                            <li class="active">Detailed DCR Report</li>
                        </ul>
                    </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row invisible-row">
                                    <div class="invisible-row" style="padding: 10px;">
                                        <form class="form-inline">
                                            <div class="form-group mb-2">
                                                <label>From Date: </label>
                                                <input id="date_range_from" type="date" <?php if (isset($_GET['date_from'])) { ?>value="<?php echo $_GET['date_from']; ?>" <?php } ?> name="date_from" onchange="selectDate()">
                                            </div>
                                            <div class="form-group mb-2">
                                                <label>To Date: </label>
                                                <input id="date_range_to" type="date" <?php if (isset($_GET['date_to'])) { ?>value="<?php echo $_GET['date_to']; ?>" <?php } ?> name="date_to" onchange="selectDate()">
                                            </div>
                                            <div class="form-group mx-sm-3 mb-2 col-sm-offset-1">
                                                <label>Select Group: </label>
                                                <select name="group" id="group" onchange="selectDate()">
                                                    <option <?php if (isset($_GET['course_group'])) if ($_GET['course_group'] == 'JR') { ?> selected="true" <?php } ?>value="JR">JR</option>
                                                    <option <?php if (isset($_GET['course_group'])) if ($_GET['course_group'] == 'DC') { ?> selected="true" <?php } ?>value="DC">DC</option>
                                                    <option <?php if (isset($_GET['course_group'])) if ($_GET['course_group'] == 'NG') { ?> selected="true" <?php } ?>value="NG">NG</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <hr class="invisible-row">
                                <?php if (!empty($headers)) { ?>
                                    <div class="col-xs-12" style="border: black solid 1px; padding: 1px; border-radius: 5px;">
                                        <div class="col-md-12">
                                            <div class="col-xs-2">
                                                <img class="nav-user-photo" src="assets/images/gallery/acsc_logo.jpeg" width="40" height="40" alt="College Logo" />
                                            </div>
                                            <div class="col-xs-10">
                                                Jamner Taluka Education Society's<br>
                                                <b>GITABAI DATTATRAY MAHAJAN ARTS, SHRI KESHARIMAL RAJMAL NAVLAKHA COMMERCE AND MANOHARSHETH DHARIWAL SCIENCE COLLEGE, JAMNER</b>
                                                Tal. Jamner Dist. Jalgaon
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <!--<br>-->
                                    <!--<div class="row">-->
                                    <div class="table-header">
                                        <b>Detailed DCR Report : <?php echo $_GET['course_group'] ?> College (<?php echo date('d-M-Y', strtotime($_GET['date_from'])); ?>  &nbsp;to&nbsp; <?php echo date('d-M-Y', strtotime($_GET['date_to'])); ?>)</b>
                                        <div class="printing invisible-row pull-right">
                                            <button id="print" class="btn btn-default btn-sm btn-outline" onclick="print()"> <span><i class="fa fa-print"></i> Print</span> </button>
                                        </div>
                                    </div>
                                    <div class="table-body">

                                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student Name (course)</th>
                                                    <!--<th>Fee Type</th>-->
                                                    <th>Invoice No.</th>
                                                    <?php if ($_SESSION['user'] == 'Anup Patil') { ?>
                                                        <th>Invoice By</th>
                                                        <?php
                                                    }
                                                    if (!empty($headers)) {
                                                        for ($index1 = 0; $index1 < count($headers); $index1++) {
                                                            ?>
                                                            <?php if (($index1 % 3 == 0)) { ?>
                                                                <th style="text-align: center;">
                                                                    <span class="text-right">
                                                                        <?php
                                                                        echo $headers[$index1];
                                                                        $index1++;
                                                                        ?> 
                                                                    </span>
                                                                    <span class="text-right">
                                                                        <?php
                                                                        if ($index1 < count($headers)) {
                                                                            ?>
                                                                            <hr style="margin-top: 2px!important; margin-bottom: 2px!important; border-top: 1px solid gray;">
                                                                            <?php
                                                                            echo $headers[$index1];
                                                                            $index1++;
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                    <span class="text-right">
                                                                        <?php
                                                                        if ($index1 < count($headers)) {
                                                                            ?>
                                                                            <hr style="margin-top: 2px!important; margin-bottom: 2px!important; border-top: 1px solid gray;">
                                                                            <?php
                                                                            echo $headers[$index1];
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                    <!--</p>-->
                                                                </th>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <th style="text-align: center;">
                                                        Paid Fees
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($data)) {
                                                    $total_of_col = array_fill(0, count($headers), 0);
                                                    $total_of_total = 0;
                                                    for ($index = 0; $index < count($data); $index++) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index + 1; ?></td>
                                                            <td><?php echo $data[$index]['first_name'] . ' ' . $data[$index]['middle_name'] . ' ' . $data[$index]['last_name'] . ' <br><b>(' . $data[$index]['course_name'] . ')</b>'; ?></td>
                                                            <!--<td><?php // echo strtoupper($data[$index]['fee_type']);      ?></td>-->
                                                            <td><?php echo $data[$index]['invoice_no']; ?></td>
                                                            <?php if ($_SESSION['user'] == 'Anup Patil') { ?>
                                                                <td><?php echo $data[$index]['invoice_by']; ?></td>
                                                            <?php } ?>
                                                            <?php
                                                            $f = json_decode($data[$index]['invoice_data']);
                                                            $total_of_row = 0;
                                                            for ($index1 = 0; $index1 < count($headers); $index1++) {
                                                                if (($index1 % 3 == 0)) {
                                                                    ?>
                                                                    <td style="text-align: center;">
                                                                        <p>
                                                                            <span class="text-right">
                                                                                <?php
                                                                                $key = str_replace(' ', '_', $headers[$index1]);
//                                                                                echo $key;
                                                                                if (property_exists($f, $key)) {
                                                                                    echo $f->$key;
                                                                                    $total_of_row += (int) $f->$key;
                                                                                    $total_of_col[$index1] += (int) $f->$key;
                                                                                } else {
                                                                                    echo '-';
                                                                                }
                                                                                $index1++;
                                                                                ?>
                                                                            </span><br>

                                                                            <span class="text-right">
                                                                                <?php
                                                                                if ($index1 < count($headers)) {
                                                                                    $key = str_replace(' ', '_', $headers[$index1]);
                                                                                    if (property_exists($f, $key)) {
                                                                                        echo $f->$key;
                                                                                        $total_of_row += (int) $f->$key;
                                                                                        $total_of_col[$index1] += (int) $f->$key;
                                                                                    } else {
                                                                                        echo '-';
                                                                                    }
                                                                                    $index1++;
                                                                                }
                                                                                ?>
                                                                            </span><br>

                                                                            <span class="text-right">
                                                                                <?php
                                                                                if ($index1 < count($headers)) {
                                                                                    $key = str_replace(' ', '_', $headers[$index1]);
                                                                                    if (property_exists($f, $key)) {
                                                                                        echo $f->$key;
                                                                                        $total_of_row += (int) $f->$key;
                                                                                        $total_of_col[$index1] += (int) $f->$key;
                                                                                    } else {
                                                                                        echo '-';
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </span>
                                                                        </p>
                                                                    </td>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <td style="text-align: center;"><?php
//                                                                    if ($data[$index]['fee_type'] == 'non paying') {
//                                                                        echo ((int) $data[$index]['course_total_fees'] - (int) $tutuion_fees[$index]) . '<br>';
////                                                                        echo (int) $data[$index]['course_total_fees']."=<br";
////                                                                        echo $tutuion_fees[$index];
//                                                                    } else {
//                                                                        echo $data[$index]['course_total_fees'] . '<br>';
//                                                                    }

                                                                echo '<b>' . $total_of_row . '</b>';
//                                                                            . '<hr style="margin-top: 2px!important; margin-bottom: 2px!important; border-top: 1px solid gray;">';
//                                                                    if ($data[$index]['fee_type'] == 'non paying') {
//                                                                        echo '<b>' . ((int) $data[$index]['course_total_fees'] - (int) $tutuion_fees[$index] - (int) $total_of_row) . '</b><br>';
//                                                                    } else {
//                                                                        echo '<b>' . ((int) $data[$index]['course_total_fees'] - $total_of_row) . '</b><br>';
//                                                                    }
//                                                                    $total_of_total += $total_of_row;
                                                                ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php }
                                                ?>
                                            </tbody>
                                        </table>

                                    </div>
                                    <!--<button class="btn btn-primary" id="exportButton">Export To Excel</button>-->
                                    <!--</div>-->
                                <?php } ?>
                                <!-- PAGE CONTENT ENDS -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <div class="footer">
                <div class="footer-inner">
                    <div class="footer-content">
                        <span class="bigger-120">
                            <span class="blue bolder">ACSC</span>
                            Jamner &copy; 2020-2021
                        </span>

                        &nbsp; &nbsp;
                        <span class="action-buttons">
                            <a href="#">
                                <i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
                            </a>

                            <a href="#">
                                <i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
                            </a>

                            <a href="#">
                                <i class="ace-icon fa fa-rss-square orange bigger-150"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->

        <!-- basic scripts -->

        <!--[if !IE]> -->
        <script src="assets/js/jquery-2.1.4.min.js"></script>

        <!-- <![endif]-->

        <!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
        <script type="text/javascript">
                                                if ('ontouchstart' in document.documentElement)
                                                    document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
        </script>
        <script src="assets/js/bootstrap.min.js"></script>

        <!-- page specific plugin scripts -->
        <script src="assets/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/jquery.dataTables.bootstrap.min.js"></script>
        <script src="assets/js/dataTables.buttons.min.js"></script>
        <script src="assets/js/buttons.flash.min.js"></script>
        <script src="assets/js/buttons.html5.min.js"></script>
        <script src="assets/js/buttons.print.min.js"></script>
        <script src="assets/js/buttons.colVis.min.js"></script>
        <script src="assets/js/dataTables.select.min.js"></script>

        <!-- ace scripts -->
        <script src="assets/js/ace-elements.min.js"></script>
        <script src="assets/js/ace.min.js"></script>

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
//                                            function getData() {
//                                                if ($('#group').val()) {
//                                                    window.location.replace("?course_group=" + $('#group').val());
//                                                }
//                                            }
                                                function selectDate() {
                                                    if ($('#group').val() && $('#date_range_from').val() && ($('#date_range_to').val()))
                                                        window.location.replace("?course_group=" + $('#group').val() + "&date_from=" + $("#date_range_from").val() + "&date_to=" + $("#date_range_to").val());
                                                }
                                                function exportTableToExcel(tableID, filename = '') {
                                                    var downloadLink;
                                                    +"&date_from=" + $('#date_range_from').val()
                                                    var dataType = 'application/vnd.ms-excel';
                                                    var tableSelect = document.getElementById(tableID);

                                                    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

                                                    // Specify file name
                                                    filename = filename ? filename + '.xls' : 'excel_data.xls';

                                                    // Create download link element
                                                    downloadLink = document.createElement("a");

                                                    document.body.appendChild(downloadLink);

                                                    if (navigator.msSaveOrOpenBlob) {
                                                        var blob = new Blob(['\ufeff', tableHTML], {
                                                            type: dataType
                                                        });
                                                        navigator.msSaveOrOpenBlob(blob, filename);
                                                    } else {
                                                        // Create a link to the file
                                                        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                                                        // Setting the file name
                                                        downloadLink.download = filename;

                                                        //triggering the function
                                                        downloadLink.click();
                                                    }
                                                }
                                                jQuery(function ($) {
                                                    //initiate dataTables plugin
                                                    if ($('#course').val()) {
                                                        $("#selceted_course").text($("#course option:selected").text());
                                                    }
                                                    $('#exportButton').click(function () {
                                                        exportTableToExcel('dynamic-table', 'tmpFile');
                                                    });
                                                    var myTable =
                                                            $('#dynamic-table')
                                                            .wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                                                            .DataTable({
                                                                bAutoWidth: false,
//                                                                    "aoColumns": [],
                                                                "aaSorting": [],
                                                                "ordering": false,
                                                                //"bProcessing": true,
                                                                //"bServerSide": true,
                                                                //"sAjaxSource": "http://127.0.0.1/table.php"	,

                                                                //,
                                                                //"sScrollY": "200px",
                                                                "bPaginate": false,
                                                                "searching": false,
                                                                //"sScrollX": "100%",
                                                                //"sScrollXInner": "120%",
                                                                //"bScrollCollapse": true,
                                                                //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
                                                                //you may want to wrap the table inside a "div.dataTables_borderWrap" element

                                                                //"iDisplayLength": 50


                                                                select: {
                                                                    style: 'multi'
                                                                }
                                                            });



//                                                        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

//                                                        new $.fn.dataTable.Buttons(myTable, {
//                                                            buttons: [
//                                                                {
//                                                                    "extend": "colvis",
//                                                                    "text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
//                                                                    "className": "btn btn-white btn-primary btn-bold",
//                                                                    columns: ':not(:first):not(:last)'
//                                                                },
//                                                                {
//                                                                    "extend": "copy",
//                                                                    "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
//                                                                    "className": "btn btn-white btn-primary btn-bold"
//                                                                },
//                                                                {
//                                                                    "extend": "csv",
//                                                                    "text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
//                                                                    "className": "btn btn-white btn-primary btn-bold"
//                                                                },
//                                                                {
//                                                                    "extend": "excel",
//                                                                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
//                                                                    "className": "btn btn-white btn-primary btn-bold"
//                                                                },
//                                                                {
//                                                                    "extend": "pdf",
//                                                                    "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
//                                                                    "className": "btn btn-white btn-primary btn-bold"
//                                                                },
//                                                                {
//                                                                    "extend": "print",
//                                                                    "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
//                                                                    "className": "btn btn-white btn-primary btn-bold",
//                                                                    autoPrint: true,
//                                                                    message: 'This print was produced using the Print button for DataTables'
////                                    message: 'Daily '.$('.table-header').html()
//                                                                }
//                                                            ]
//                                                        });
//                                                        myTable.buttons().container().appendTo($('.tableTools-container'));

                                                    //style the message box
                                                    var defaultCopyAction = myTable.button(1).action();
                                                    myTable.button(1).action(function (e, dt, button, config) {
                                                        defaultCopyAction(e, dt, button, config);
                                                        $('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
                                                    });


                                                    var defaultColvisAction = myTable.button(0).action();
                                                    myTable.button(0).action(function (e, dt, button, config) {

                                                        defaultColvisAction(e, dt, button, config);


                                                        if ($('.dt-button-collection > .dropdown-menu').length == 0) {
                                                            $('.dt-button-collection')
                                                                    .wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
                                                                    .find('a').attr('href', '#').wrap("<li />")
                                                        }
                                                        $('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
                                                    });

                                                    ////

                                                    setTimeout(function () {
                                                        $($('.tableTools-container')).find('a.dt-button').each(function () {
                                                            var div = $(this).find(' > div').first();
                                                            if (div.length == 1)
                                                                div.tooltip({container: 'body', title: div.parent().text()});
                                                            else
                                                                $(this).tooltip({container: 'body', title: $(this).text()});
                                                        });
                                                    }, 500);





                                                    myTable.on('select', function (e, dt, type, index) {
                                                        if (type === 'row') {
                                                            $(myTable.row(index).node()).find('input:checkbox').prop('checked', true);
                                                        }
                                                    });
                                                    myTable.on('deselect', function (e, dt, type, index) {
                                                        if (type === 'row') {
                                                            $(myTable.row(index).node()).find('input:checkbox').prop('checked', false);
                                                        }
                                                    });




                                                    /////////////////////////////////
                                                    //table checkboxes
                                                    $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

                                                    //select/deselect all rows according to table header checkbox
                                                    $('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function () {
                                                        var th_checked = this.checked;//checkbox inside "TH" table header

                                                        $('#dynamic-table').find('tbody > tr').each(function () {
                                                            var row = this;
                                                            if (th_checked)
                                                                myTable.row(row).select();
                                                            else
                                                                myTable.row(row).deselect();
                                                        });
                                                    });

                                                    //select/deselect a row when the checkbox is checked/unchecked
                                                    $('#dynamic-table').on('click', 'td input[type=checkbox]', function () {
                                                        var row = $(this).closest('tr').get(0);
                                                        if (this.checked)
                                                            myTable.row(row).deselect();
                                                        else
                                                            myTable.row(row).select();
                                                    });



                                                    $(document).on('click', '#dynamic-table .dropdown-toggle', function (e) {
                                                        e.stopImmediatePropagation();
                                                        e.stopPropagation();
                                                        e.preventDefault();
                                                    });



                                                    //And for the first simple table, which doesn't have TableTools or dataTables
                                                    //select/deselect all rows according to table header checkbox
                                                    var active_class = 'active';
                                                    $('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function () {
                                                        var th_checked = this.checked;//checkbox inside "TH" table header

                                                        $(this).closest('table').find('tbody > tr').each(function () {
                                                            var row = this;
                                                            if (th_checked)
                                                                $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
                                                            else
                                                                $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
                                                        });
                                                    });

                                                    //select/deselect a row when the checkbox is checked/unchecked
                                                    $('#simple-table').on('click', 'td input[type=checkbox]', function () {
                                                        var $row = $(this).closest('tr');
                                                        if ($row.is('.detail-row '))
                                                            return;
                                                        if (this.checked)
                                                            $row.addClass(active_class);
                                                        else
                                                            $row.removeClass(active_class);
                                                    });



                                                    /********************************/
                                                    //add tooltip for small view action buttons in dropdown menu
                                                    $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});

                                                    //tooltip placement on right or left
                                                    function tooltip_placement(context, source) {
                                                        var $source = $(source);
                                                        var $parent = $source.closest('table')
                                                        var off1 = $parent.offset();
                                                        var w1 = $parent.width();

                                                        var off2 = $source.offset();
                                                        //var w2 = $source.width();

                                                        if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2))
                                                            return 'right';
                                                        return 'left';
                                                    }




                                                    /***************/
                                                    $('.show-details-btn').on('click', function (e) {
                                                        e.preventDefault();
                                                        $(this).closest('tr').next().toggleClass('open');
                                                        $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
                                                    });
                                                    /***************/





                                                    /**
                                                     //add horizontal scrollbars to a simple table
                                                     $('#simple-table').css({'width':'2000px', 'max-width': 'none'}).wrap('<div style="width: 1000px;" />').parent().ace_scroll(
                                                     {
                                                     horizontal: true,
                                                     styleClass: 'scroll-top scroll-dark scroll-visible',//show the scrollbars on top(default is bottom)
                                                     size: 2000,
                                                     mouseWheelLock: true
                                                     }
                                                     ).css('padding-top', '12px');
                                                     */


                                                })
        </script>
    </body>
</html>
