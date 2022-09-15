<?php
session_start();
include 'data_manipulator_config.php';
$link = config();
$user_data = [];
//$selected_course = (isset($_GET['course'])) ? $_GET['course'] : 1;
//$condition = "WHERE a.class ='" . $selected_course . "'";

$sql = "SELECT "
        . "a.id,a.class,a.invoice_id,a.first_name, a.middle_name, a.last_name,"
        . "c.course_name, "
        . "c.next_course, "
        . "c.next_course_admission_open, "
        . "c.fees, "
        . "c.fees_breakup, "
        . "c.course_group, "
        . "a.gender, "
        . "a.fee_type, "
        . "a.student_roll_number,"
        . "a.phone_nmuber as phone_number,"
        . "a.created_by,"
        . "a.category,"
        . "a.created_date,"
        . "r.data,"
        . "r.repayment_dates "
        . "FROM cancelled_admission_form a "
        . "JOIN course c on c.id=a.class "
        . "JOIN cancelled_repayment r on r.admission_id=a.id";
$result = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($result, $user_data);
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

$sql = "SELECT active_admission_id from cancelled_students";
$stds = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($stds, $user_data);
    }
} else {
    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
}
$students = [];
for ($i = 0; $i < count($stds); $i++) {
    array_push($students, $stds[$i]['active_admission_id']);
}

$sql = "SELECT * from course";
$course = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($course, $user_data);
    }
} else {
    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
}

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
                    <div class="breadcrumbs breadcrumbs-fixed " id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <a href="#">Home</a>
                            </li>

                            <li>
                                <a href="#">Admissions</a>
                            </li>
                            <li class="active">List of Admissions</li>
                        </ul><!-- /.nav-search -->
                    </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="table-header">
                                            Cancelled Admissions
                                            <!--<div class="form-group">-->
                                            <!--<label class="col m-l-1" for="form-field-1"> Please select Course: </label>-->

                                            <!--</div>-->
                                            <div class="pull-right tableTools-container"></div>
                                            <div class="printing invisible-row pull-right">
                                                <button id="print" class="btn btn-default btn-sm btn-outline" onclick="print()"> <span><i class="fa fa-print"></i> Print</span> </button>
                                            </div>
                                        </div>
                                        <div>
                                            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student Name</th>
                                                        <th>Course (Total Fees)</th>
                                                        <th >Gender</th>
                                                        <th class="hidden-480">Roll Number</th>
                                                        <th class="hidden-480">Applicable Fees</th>
                                                        <th class="hidden-480">Paid Fees</th>
                                                        <th class="hidden-480">Admission Date</th>
                                                        <?php if ($_SESSION['user'] == "Anup Patil") { ?>
                                                            <th class="hidden-480">Action</th>
                                                        <?php } ?>
                                                <!--<th class="hidden-480">Invoice Reprint</th>-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($index = 0; $index < count($result); $index++) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $index + 1; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $result[$index]['first_name'] . " " . $result[$index]['middle_name'] . " " . $result[$index]['last_name']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $result[$index]['course_name'] . ' (' . $result[$index]['fees'] . ')'; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $result[$index]['gender']; ?>
                                                            </td>
                                                            <td class="hidden-480">
                                                                <?php echo $result[$index]['student_roll_number']; ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $tutuion_fees = 0;
                                                                $r = json_decode($result[$index]['fees_breakup']);
                                                                for ($a = 0; $a < count($r); $a++) {
                                                                    if ($r[$a]->header == 'TUITION FEE')
                                                                        $tutuion_fees = $r[$a]->value;
                                                                    if ($r[$a]->header == 'ADMISSION FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'ADMISSION FFE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'LIBRARY FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'MEDICAL FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'GYMKHANA FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'POOR STUD AID FUND')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'STUDENTS ACTIVITY FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'LABORATORY FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'STUDENTS GROUP INS (UNI)')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'COMPUTERIZATION FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'COLLEGE DEVELOPMENT FUND')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'ASHWAMEDH FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'IDENTITY FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'YUVA RANG/YOUTH FESTIVEL')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                }
                                                                $applicable_fees = 0;
                                                                if ($result[$index]['fee_type'] == 'non paying')
                                                                    $applicable_fees = (int) $result[$index]['fees'] - $tutuion_fees;
                                                                else
                                                                    $applicable_fees = (int) $result[$index]['fees'];
                                                                echo $applicable_fees;
                                                                ?>
                                                            </td>
                                                            <td class="hidden-480">
                                                                <?php
                                                                $d = json_decode('[' . $result[$index]['data'] . ']');
                                                                $paid_amount = 0;
                                                                for ($i = 0; $i < count($d); $i++) {
                                                                    $arr_keys = array_keys((array) $d[$i]);
                                                                    $arr_data = ((array) $d[$i]);
                                                                    for ($j = 0; $j < count($arr_keys); $j++) {
                                                                        $paid_amount = $paid_amount + (int) $arr_data[$arr_keys[$j]];
                                                                    }
                                                                }
                                                                echo $paid_amount;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php echo date_format(date_create($result[$index]['created_date']), "d-m-Y"); ?>
                                                            </td>
                                                            <?php if ($_SESSION['user'] == "Anup Patil") { ?>
                                                                <td class="hidden-480">
                                                                    <a class="btn btn-danger btn-xs m-1 disabled" id="<?php echo $result[$index]['id']; ?>">Revert Cancellation</a>
                                                                </td>
                                                            <?php } ?>
    <!--                                                            <td>
                                                            <?php
                                                            $repayment_invoice_ids = json_decode('[' . $result[$index]['repayment_dates'] . ']');
                                                            for ($index1 = 0, $temp = 1; $index1 < count($repayment_invoice_ids); $index1++, $temp++) {
                                                                ?>
                                                                    <a href="invoice.php?invoice_id=<?php
                                                                if ($index1 == 0) {
                                                                    echo $result[$index]['invoice_id'];
                                                                } else {
                                                                    echo $repayment_invoice_ids[$index1]->invoice_id;
                                                                }
                                                                ?>&form=<?php echo $result[$index]['id']; ?>&class=<?php echo $result[$index]['class']; ?>&reprint=1">
                                                                <?php echo 'Inv No.' . $temp;
                                                                ?>

                                                                <?php
                                                                if ($_SESSION['user'] == 'Anup Patil') {
                                                                    $inv_amount = 0;
                                                                    $fee_arr = array_keys((array) json_decode($result[$index]['data']));
                                                                    $fee = (array) json_decode($result[$index]['data']);
                                                                    for ($a = 0; $a < count($fee_arr); $a++) {
                                                                        $inv_amount+=$fee[$fee_arr[$a]];
                                                                    }
                                                                    ?>
                                                                    <?php
                                                                    echo '(' . $inv_amount . ')';
                                                                }
                                                                ?>
                                                                    </a>&nbsp;&nbsp;&nbsp;&nbsp;<?php
                                                            }
                                                            ?>
                                                    </td>-->
                                                        </tr>
                                                    <?php }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- PAGE CONTENT ENDS -->
                            </div><!-- /.col -->
                        </div>
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
        <script src="assets/js/bootbox.js"></script>
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
                                                    function confirmDelete(admission_form_id) {
                                                        var txt;
                                                        var r = confirm("Are you Sure, You want to update this record!");
                                                        if (r == true) {
                                                            window.location.replace("update-admission-form.php?form=" + admission_form_id);
                                                        }
                                                    }
                                                    function getData() {
                                                        if ($('#course').val())
                                                            window.location.replace("?course=" + $('#course').val());
                                                    }
                                                    jQuery(function ($) {
                                                        $(".pay_dues").click(function () {
                                                            window.location.replace("make-repayment.php?form=" + this.id);
                                                        })
                                                        $(".cancel_admission").click(function () {
                                                            var r = confirm("Are you Sure, You want to delete this Admission record!");
                                                            if (r == true) {
                                                                var request = $.ajax({
                                                                    url: "cancel-admissions.php?form_id=" + this.id,
                                                                    type: "get"
                                                                });
                                                                request.done(function (msg) {
                                                                    var obj = JSON.parse(msg);
                                                                    console.log(obj)
                                                                    bootbox.dialog({
                                                                        message: obj.success === true ? "Thank you! Admission was cancelled!" : obj.message,
                                                                        buttons: {
                                                                            "success": {
                                                                                "label": "OK",
                                                                                "className": "btn-sm btn-primary"
                                                                            }
                                                                        }
                                                                    });
//                                                                                    window.location.reload();
                                                                });
                                                                request.fail(function (jqXHR, textStatus) {
                                                                    console.log("Request failed: " + textStatus);
                                                                    console.log("Request failed message: " + jqXHR);
                                                                    bootbox.dialog({
                                                                        message: "Opps! Problem in canceling! Please check console.",
                                                                        buttons: {
                                                                            "success": {
                                                                                "label": "Error",
                                                                                "className": "btn-sm btn-error"
                                                                            }
                                                                        }
                                                                    });
                                                                });
                                                            }
                                                        })
                                                        $(".get-student-info").click(function () {
                                                            var request = $.ajax({
                                                                url: "create-student.php?form_id=" + this.id,
                                                                type: "get"
                                                            });
                                                            request.done(function (msg) {
                                                                var obj = JSON.parse(msg);
                                                                console.log(obj)
                                                                bootbox.dialog({
                                                                    message: "Thank you! Student record was saved successfully!",
                                                                    buttons: {
                                                                        "success": {
                                                                            "label": "OK",
                                                                            "className": "btn-sm btn-primary"
                                                                        }
                                                                    }
                                                                });
                                                                window.location.reload();
                                                            });
                                                            request.fail(function (jqXHR, textStatus) {
                                                                console.log("Request failed: " + textStatus);
                                                                console.log("Request failed message: " + jqXHR);
                                                                bootbox.dialog({
                                                                    message: "Opps! Problem in saving student record! Please check console.",
                                                                    buttons: {
                                                                        "success": {
                                                                            "label": "Error",
                                                                            "className": "btn-sm btn-error"
                                                                        }
                                                                    }
                                                                });
                                                            });
                                                        })
                                                        $(".cancel-admission").click(function () {
                                                            var request = $.ajax({
                                                                url: "cancel-admission.php?form_id=" + this.id,
                                                                type: "get"
                                                            });
                                                            request.done(function (msg) {
                                                                var obj = JSON.parse(msg);
                                                                console.log(obj)
                                                                bootbox.dialog({
                                                                    message: "Admission and it's details cancelled!",
                                                                    buttons: {
                                                                        "success": {
                                                                            "label": "OK",
                                                                            "className": "btn-sm btn-primary"
                                                                        }
                                                                    }
                                                                });
                                                                window.location.reload();
                                                            });
                                                            request.fail(function (jqXHR, textStatus) {
                                                                console.log("Request failed: " + textStatus);
                                                                console.log("Request failed message: " + jqXHR);
                                                                bootbox.dialog({
                                                                    message: "Problem in cancelling Admission and it's details! Please check console.",
                                                                    buttons: {
                                                                        "success": {
                                                                            "label": "Error",
                                                                            "className": "btn-sm btn-error"
                                                                        }
                                                                    }
                                                                });
                                                            });
                                                        })
                                                        //initiate dataTables plugin
                                                        var myTable =
                                                                $('#dynamic-table')
                                                                .DataTable({
                                                                    bAutoWidth: false,
                                                                    "aaSorting": [],
                                                                    select: {
                                                                        style: 'multi'
                                                                    }
                                                                });
                                                        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
                                                        new $.fn.dataTable.Buttons(myTable, {
                                                            buttons: [
                                                                {
                                                                    "extend": "colvis",
                                                                    "text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
                                                                    "className": "btn btn-white btn-primary btn-bold",
//                                                                                    columns: ':not(:first):not(:last)'
                                                                },
                                                                {
                                                                    "extend": "copy",
                                                                    "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                                                                    "className": "btn btn-white btn-primary btn-bold"
                                                                },
                                                                {
                                                                    "extend": "csv",
                                                                    "text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
                                                                    "className": "btn btn-white btn-primary btn-bold"
                                                                },
                                                                {
                                                                    "extend": "excel",
                                                                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                                                                    "className": "btn btn-white btn-primary btn-bold"
                                                                },
                                                                {
                                                                    "extend": "pdf",
                                                                    "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                                                                    "className": "btn btn-white btn-primary btn-bold"
                                                                },
//                                                                                {
//                                                                                    "extend": "print",
//                                                                                    "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
//                                                                                    "className": "btn btn-white btn-primary btn-bold",
//                                                                                    autoPrint: true,
//                                                                                    message: "<div class='col-xs-12' style='border: gray solid 1px; padding: 10px;'><div class='col-md-12'><div class='col-xs-10'>Jamner Taluka Education Society's<br><b>GITABAI DATTATRAY MAHAJAN ARTS, SHRI KESHARIMAL RAJMAL NAVLAKHA COMMERCE AND MANOHARSHETH DHARIWAL SCIENCE COLLEGE, JAMNER</b><br>Tal. Jamner Dist. Jalgaon</div></div></div>"
//                                                                                }
                                                            ]
                                                        });
                                                        myTable.buttons().container().appendTo($('.tableTools-container'));

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
                                                        $('.show-details-btn').on('click', function (e) {
                                                            e.preventDefault();
                                                            $(this).closest('tr').next().toggleClass('open');
                                                            $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
                                                        });
                                                    })
        </script>
    </body>
</html>
