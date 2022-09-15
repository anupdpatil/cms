<?php
session_start();
$user_data = [];
include 'data_manipulator_config.php';
$link = config();
$clause = '';
$is_clause_set = FALSE;
if (isset($_GET['course'])) {
    $is_clause_set = TRUE;
    $clause .= "c.course_name='" . htmlspecialchars($_GET['course']) . "' AND ";
}
if (isset($_GET['gender'])) {
    $is_clause_set = TRUE;
    $clause .= "a.gender='" . $_GET['gender'] . "' AND ";
}
if (isset($_GET['category'])) {
    $is_clause_set = TRUE;
    $clause .= "a.category='" . htmlspecialchars($_GET['category']) . "' AND ";
}
if (isset($_GET['fee_type'])) {
    $is_clause_set = TRUE;
    $clause .= "a.fee_type='" . htmlspecialchars($_GET['fee_type']) . "' AND ";
}

if ($is_clause_set)
    $clause = " WHERE " . substr($clause, 0, -5);

$sql = "SELECT "
        . "a.id,a.class,a.invoice_id,a.first_name, a.middle_name, a.last_name,"
        . "c.course_name, "
        . "c.fees, "
        . "c.fees_breakup, "
        . "a.gender, "
        . "a.fee_type, "
        . "a.student_roll_number,"
        . "a.phone_nmuber as phone_number,"
        . "a.created_by,"
        . "a.category,"
        . "a.created_date,"
        . "r.data,"
        . "r.repayment_dates "
        . "FROM admission_form a "
        . "JOIN course c on c.id=a.class "
        . "JOIN repayment r on r.admission_id=a.id "
        . "$clause";
$result = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($result, $user_data);
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

$sql = "SELECT * from course";
$list_of_courses = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($list_of_courses, $user_data);
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
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
                    <div class="breadcrumbs breadcrumbs-fixed  invisible-row" id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <a href="#">Home</a>
                            </li>
                            <li> <a href="#">Admissions</a> </li>
                            <li class="active">List of Admissions</li>
                        </ul>
                    </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="invisible-row">
                                                    <form class="form-inline">
                                                        <div class="form-group mx-sm-3 mb-2">
                                                            <label>Course: </label>
                                                            <select name="course" id="course" onchange="getData()">
                                                                <option value="">---Course Name---</option>
                                                                <?php for ($course = 0; $course < count($list_of_courses); $course++) { ?>
                                                                    <?php if (isset($_GET['course']) && ($_GET['course'] == $list_of_courses[$course]['course_name'])) { ?>
                                                                        <option  selected="true" value="<?php echo $list_of_courses[$course]['course_name']; ?>"><?php echo $list_of_courses[$course]['course_name']; ?></option>
                                                                    <?php } else { ?>
                                                                        <option value="<?php echo $list_of_courses[$course]['course_name']; ?>"><?php echo $list_of_courses[$course]['course_name']; ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mx-sm-3 mb-2">
                                                            <label>Gender</label>
                                                            <select name="gender" id="gender" onchange="getData()">
                                                                <option value="">---Gender---</option>
                                                                <option <?php if (isset($_GET['gender']) && ($_GET['gender'] == 'male')) { ?> selected="true"<?php } ?>value="male">Male</option>
                                                                <option <?php if (isset($_GET['gender']) && ($_GET['gender'] == 'female')) { ?> selected="true"<?php } ?>value="female">Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mx-sm-3 mb-2">
                                                            <label>Fee Type: </label>
                                                            <select name="fee_type" id="fee_type" onchange="getData()">
                                                                <option value="">---Feet Type---</option>
                                                                <option <?php if (isset($_GET['fee_type']) && ($_GET['fee_type'] == 'paying')) { ?> selected="true"<?php } ?>value="paying">Paying</option>
                                                                <option <?php if (isset($_GET['fee_type']) && ($_GET['fee_type'] == 'non paying')) { ?> selected="true"<?php } ?>value="non paying">Non Paying (GOI)</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mx-sm-3 mb-2">
                                                            <label>Category: </label>
                                                            <select name="category" id="category" onchange="getData()">
                                                                <option value="">--- Category ---</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'SC')) { ?> selected="true"<?php } ?>value="SC">SC</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'ST')) { ?> selected="true"<?php } ?>value="ST">ST</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'VJ NT (A)')) { ?> selected="true"<?php } ?>value="VJ NT (A)">VJ NT (A)</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'NT B')) { ?> selected="true"<?php } ?>value="NT B">NT B</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'NT C')) { ?> selected="true"<?php } ?>value="NT C">NT C</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'NT D')) { ?> selected="true"<?php } ?>value="NT D">NT D</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'SBC')) { ?> selected="true"<?php } ?>value="SBC">SBC</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'OBC')) { ?> selected="true"<?php } ?>value="OBC">OBC</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'E.S.B.C.')) { ?> selected="true"<?php } ?>value="E.S.B.C.">E.S.B.C.</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'GENERAL')) { ?> selected="true"<?php } ?>value="GENERAL">GENERAL</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'PTWF')) { ?> selected="true"<?php } ?>value="PTWF">PTWF</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'HSTWF')) { ?> selected="true"<?php } ?>value="HSTWF">HSTWF</option>
                                                                <option <?php if (isset($_GET['category']) && ($_GET['category'] == 'Freedom fighter')) { ?> selected="true"<?php } ?>value="Freedom fighter">Freedom fighter</option>
                                                            </select>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
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
                                        <div class="table-header">
                                            Paid And Outstanding Fees Report (Short)
                                            <?php
                                            if (isset($_GET['course'])) {
                                                echo 'For ' . $_GET['course'];
                                            }
                                            ?>
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
                                                        <th class="hidden-480">Applicable Fees</th>
                                                        <th class="hidden-480">Paid Fees</th>
                                                        <th class="hidden-480">Remaining Fees</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $total_applicable_fees = 0;
                                                    $total_paid_amount = 0;
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
                                                                    if ($r[$a]->header == 'STUDENTS GROUP INS (UNI) FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'COMPUTERIZATION FEE')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'COLLEGE DEVELOPMENT FUND')
                                                                        $tutuion_fees += $r[$a]->value;
                                                                    if ($r[$a]->header == 'COLLEGE DEVELOPMENT FUND FEE')
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
                                                                $total_applicable_fees += $applicable_fees;
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
                                                                $total_paid_amount += $paid_amount;
                                                                ?>
                                                            </td>
                                                            <td class="hidden-480"><?php echo $applicable_fees - $paid_amount; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td colspan="3" style="text-align: center;"><b>Total</b></td>
                                                        <td><b><?php echo $total_applicable_fees; ?></b></td>
                                                        <td><b><?php echo $total_paid_amount; ?></b></td>
                                                        <td><b><?php echo $total_applicable_fees - $total_paid_amount; ?></b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
                                                    function getData() {
                                                        var url = '';
                                                        var url_created = false;
                                                        if ($('#course').val()) {
                                                            url += 'course=' + $('#course').val() + "&";
                                                            url_created = true;
                                                        }
                                                        if ($('#gender').val()) {
                                                            url += 'gender=' + $('#gender').val() + "&";
                                                            url_created = true;
                                                        }
                                                        if ($('#category').val()) {
                                                            url += 'category=' + $('#category').val() + "&";
                                                            url_created = true;
                                                        }
                                                        if ($('#fee_type').val()) {
                                                            url += 'fee_type=' + $('#fee_type').val() + "&";
                                                            url_created = true;
                                                        }

                                                        if (url_created) {
                                                            window.location.replace("?" + url.slice(0, -1));
                                                        }
                                                    }
                                                    jQuery(function ($) {
                                                        //initiate dataTables plugin
                                                        var myTable =
                                                                $('#dynamic-table')
                                                                .DataTable({
                                                                    bAutoWidth: false,
                                                                    "aaSorting": [],
                                                                    "bPaginate": false,
                                                                    "searching": false,
                                                                    select: {
                                                                        style: 'multi'
                                                                    }
                                                                });
//                                                                    $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
//                                                                    new $.fn.dataTable.Buttons(myTable, {
//                                                                        buttons: [
//                                                                            {
//                                                                                "extend": "colvis",
//                                                                                "text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
//                                                                                "className": "btn btn-white btn-primary btn-bold",
//                                                                                columns: ':not(:first):not(:last)'
//                                                                            },
//                                                                            {
//                                                                                "extend": "copy",
//                                                                                "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
//                                                                                "className": "btn btn-white btn-primary btn-bold"
//                                                                            },
//                                                                            {
//                                                                                "extend": "csv",
//                                                                                "text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
//                                                                                "className": "btn btn-white btn-primary btn-bold"
//                                                                            },
//                                                                            {
//                                                                                "extend": "excel",
//                                                                                "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
//                                                                                "className": "btn btn-white btn-primary btn-bold"
//                                                                            },
//                                                                            {
//                                                                                "extend": "pdf",
//                                                                                "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
//                                                                                "className": "btn btn-white btn-primary btn-bold"
//                                                                            },
//                                                                            {
//                                                                                "extend": "print",
//                                                                                "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
//                                                                                "className": "btn btn-white btn-primary btn-bold",
//                                                                                autoPrint: false,
//                                                                                message: "<div class='col-xs-12' style='border: gray solid 1px; padding: 10px;'><div class='col-md-12'><div class='col-xs-10'>Jamner Taluka Education Society's<br><b>GITABAI DATTATRAY MAHAJAN ARTS, SHRI KESHARIMAL RAJMAL NAVLAKHA COMMERCE AND MANOHARSHETH DHARIWAL SCIENCE COLLEGE, JAMNER</b><br>Tal. Jamner Dist. Jalgaon</div></div></div>"
//                                                                            }
//                                                                        ]
//                                                                    });
//                                                                    myTable.buttons().container().appendTo($('.tableTools-container'));

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
