<?php
session_start();
$user_data = [];
include 'data_manipulator_config.php';
$link = config();
$sql_users = "SELECT id,first_name,last_name from user WHERE role_id = 2";
$users_arr = [];
if ($user_res = mysqli_query($link, $sql_users)) {
    while ($user_data = mysqli_fetch_assoc($user_res)) {
        array_push($users_arr, $user_data);
    }
} else {
    die("ERROR: Could not able to execute $sql_users. " . mysqli_error($link));
}
$course_id_name = [];
if ((isset($_GET['group'])) && isset($_GET['date_from']) && isset($_GET['date_to'])) {
    $link = config();
    $courses = [];
    $course_wise_sql = "SELECT id,fees_breakup,course_name from course WHERE course_group = '" . $_GET['group'] . "'";
    $course_headers = [];
    if ($course_res = mysqli_query($link, $course_wise_sql)) {
        while ($course_data = mysqli_fetch_assoc($course_res)) {
            array_push($course_headers, $course_data);
        }
    } else {
        die("ERROR: Could not able to execute $course_wise_sql. " . mysqli_error($link));
    }
    $all_headers = [];
    for ($index = 0; $index < count($course_headers); $index++) {
        for ($index1 = 0; $index1 < count(json_decode($course_headers[$index]['fees_breakup'])); $index1++) {
            array_push($all_headers, (json_decode($course_headers[$index]['fees_breakup'])[$index1]->header));
        }
        array_push($courses, $course_headers[$index]['id']);
        $course_id_name[$course_headers[$index]['id']] = $course_headers[$index]['course_name'];
    }
    array_push($all_headers, 'ADMISSION FEE');
    array_push($all_headers, 'DEVELOPMENT FUND');
    array_push($all_headers, 'DEVELOPMENT FUND');
    array_push($all_headers, 'T C FEE');
    array_push($all_headers, 'SUBJECT CHANGE FEE');
    array_push($all_headers, 'FINE');
    array_push($all_headers, 'RECOVERY COST OF LOST BOOKS');
    array_push($all_headers, 'BRAKAGES');
    array_push($all_headers, 'KBC NMU EXAM FEE');
    array_push($all_headers, 'HSC EXAM FEE');
    array_push($all_headers, 'LIBRARY FINE');
    $all_headers = (array_values(array_unique($all_headers)));
    sort($all_headers);
    $course_list = implode(",", $courses);
    $st_date = date('Y-m-d H:i:s', strtotime($_GET['date_from']) + 1);
    $end_date = date('Y-m-d H:i:s', strtotime($_GET['date_to']) + ((60 * 60 * 24) - 1));
    $counter = (isset($_GET['counter'])) ? "AND i.created_by='" . $_GET['counter'] . "'" : '';

    $sql_new = "SELECT i.data, i.created_by, f.course_id "
            . "from invoices i "
            . "join fees f on f.invoice_id=i.id AND f.course_id IN ($course_list) "
            . "WHERE i.created_date between '" . $st_date . "' and '" . $end_date . "'" . $counter;
    $other_receipt_data = [];
    if ($res_temp = mysqli_query($link, $sql_new)) {
        while ($user_data = mysqli_fetch_assoc($res_temp)) {
            array_push($other_receipt_data, $user_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql_new. " . mysqli_error($link));
    }

    $sql_new = "SELECT f.data, f.repayment_dates,f.total_repayments, i.id as ad_id, i.class as course_id "
            . "from repayment f "
            . "join admission_form i on i.id = f.admission_id AND i.class IN ($course_list) "
            . "WHERE f.total_repayments <> 1 " . $counter;
    $repayment_receipt_data = [];
    if ($res_temp = mysqli_query($link, $sql_new)) {
        while ($user_data = mysqli_fetch_assoc($res_temp)) {
            array_push($repayment_receipt_data, $user_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql_new. " . mysqli_error($link));
    }
    $json_data = [];
    $repayment_dates_data = [];
    $repayment_course = [];
    $reference_json = [];
    for ($n = 0; $n < count($repayment_receipt_data); $n++) {
        array_push($json_data, json_decode(('[' . $repayment_receipt_data[$n]['data'] . ']'), true));
        array_push($repayment_dates_data, json_decode(('[' . $repayment_receipt_data[$n]['repayment_dates'] . ']'), true));
        array_push($repayment_course, array_fill(0, count(json_decode(('[' . $repayment_receipt_data[$n]['repayment_dates'] . ']'), true)), $repayment_receipt_data[$n]['course_id']));
    }
    $final_repayment_data = [];
    for ($i = 0; $i < count($json_data); $i++) {
        for ($j = 1; $j < count($json_data[$i]); $j++) {
            $date = date('Y-m-d H:i:s', strtotime($repayment_dates_data[$i][$j]['invoice_date']));
            if ($date > $st_date && $date < $end_date) {
                $temp_obj['data'] = json_encode($json_data[$i][$j]);
                $temp_obj['created_by'] = $repayment_dates_data[$i][$j]['created_by'];
                $temp_obj['course_id'] = $repayment_course[$i][$j];
                array_push($final_repayment_data, $temp_obj);
            }
        }
    }

    $sql_new = "SELECT i.data, i.created_by, af.class as course_id "
            . "from invoices i "
            . "join admission_form af on af.invoice_id=i.id AND af.class IN ($course_list) "
            . "WHERE i.created_date between '" . $st_date . "' and '" . $end_date . "'" . $counter;
    $data = [];
    if ($res_temp = mysqli_query($link, $sql_new)) {
        while ($user_data = mysqli_fetch_assoc($res_temp)) {
            array_push($data, $user_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql_new. " . mysqli_error($link));
    }
    $data = array_merge($data, $final_repayment_data);
    $data = array_merge($data, $other_receipt_data);

    $counter_wise_data = [];
    $course_wise_sum_arr = [];
    $course_wise_sum = new stdClass();
    for ($index2 = 0; $index2 < count($data); $index2++) {
        $test_obj = (json_decode($data[$index2]['data']));
        $sum = 0;
        for ($j1 = 0; $j1 < count($all_headers); $j1++) {
            $prop = str_replace(' ', '_', $all_headers[$j1]);
            if (property_exists($test_obj, $prop)) {
                $sum += json_decode($data[$index2]['data'])->$prop;
            }
        }
        $course_wise_sum->$data[$index2]['course_id'] = $sum;

        if ($index2 == 0) {
            $course_wise_sum_arr[$data[$index2]['course_id']] = $sum;
        } else
        if (array_key_exists($data[$index2]['course_id'], $course_wise_sum_arr)) {
            $course_wise_sum_arr[$data[$index2]['course_id']] += $sum;
        } else {
            $course_wise_sum_arr[$data[$index2]['course_id']] = $sum;
        }
        array_push($counter_wise_data, array('name' => $data[$index2]['created_by'], 'count' => $sum, 'course_id' => $data[$index2]['course_id']));
    }

    $final_counters = [];
    for ($index3 = 0; $index3 < count($counter_wise_data); $index3++) {
        array_push($final_counters, $counter_wise_data[$index3]['name']);
    }
    $final_counters = array_unique($final_counters);
    $final_counters = array_values($final_counters);
    $temp = array();

//    $o_data = array(0 => array('name' => 'Anup', 'count' => 200, 'id' => 1), 1 => array('name' => 'Pravin', 'count' => 20, 'id' => 1), 2 => array('name' => 'Pravin', 'count' => 500, 'id' => 2), 3 => array('name' => 'Pravin', 'count' => 200, 'id' => 2), 4 => array('name' => 'Anup', 'count' => 20, 'id' => 3));

    array_walk($counter_wise_data, function($value, $key)use(&$temp) {
        if (array_key_exists($value['name'], $temp)) {
            if (array_key_exists($value['course_id'], $temp[$value['name']])) {
                $temp[$value['name']][$value['course_id']] += $value['count'];
            } else {
                $temp[$value['name']][$value['course_id']] = $value['count'];
            }
        } else {
            $val = array($value['name'] => array($value['course_id'] => $value['count']));
            $temp[$value['name']] = [];
            $temp[$value['name']][$value['course_id']] = $value['count'];
        }
    });


    $counter_wise_report = [];
    for ($index4 = 0; $index4 < count($final_counters); $index4++) {
        $final_sum = [];
        for ($index3 = 0; $index3 < count($counter_wise_data); $index3++) {
            if ($counter_wise_data[$index3]['name'] == $final_counters[$index4]) {
                array_push($final_sum, $counter_wise_data[$index3]['count']);
            }
        }
        $counter_wise_report[$index4] = array('name' => $final_counters[$index4], 'count' => array_sum($final_sum));
    }

    $header_with_values = [];
    for ($i1 = 0; $i1 < count($all_headers); $i1++) {
        $prop = str_replace(' ', '_', $all_headers[$i1]);
        $header_with_values[$prop] = [];
        for ($i2 = 0; $i2 < count($data); $i2++) {
            $temp_obj = (json_decode($data[$i2]['data']));
            if (property_exists($temp_obj, $prop)) {
                array_push($header_with_values[$prop], json_decode($data[$i2]['data'])->$prop);
            }
        }
    }
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

                            <li>
                                <a href="#">Reports</a>
                            </li>
                            <li class="active">Fees Collection Report</li>
                        </ul>
                    </div>
                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="clearfix">
                                            <div class="pull-right tableTools-container"></div>
                                        </div>
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
                                                    <select name="group" id="group" onchange="getData()">
                                                        <option <?php if (isset($_GET['group'])) if ($_GET['group'] == 'JR') { ?> selected="true" <?php } ?>value="JR">JR</option>
                                                        <option <?php if (isset($_GET['group'])) if ($_GET['group'] == 'DC') { ?> selected="true" <?php } ?>value="DC">DC</option>
                                                        <option <?php if (isset($_GET['group'])) if ($_GET['group'] == 'NG') { ?> selected="true" <?php } ?>value="NG">NG</option>
                                                    </select>
                                                </div>
                                                <?php if (isset($_GET['group'])) { ?>
                                                    <div class="form-group mx-sm-3 mb-2 col-sm-offset-4">
                                                        <div class="printing invisible-row">
                                                            <div class="text-right">
                                                                <button id="print" class="btn btn-sm btn-default btn-outline" type="button" onclick="window.print()"> <span><i class="fa fa-print"></i> Print</span> </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        </div>
                                        <hr class="invisible-row">
                                        <?php if (isset($_GET['group'])) { ?>
                                            <div class="col-xs-10 col-xs-offset-1" style="border: black solid 1px; padding: 1px; border-radius: 2px;">
                                                <div class="col-md-12">
                                                    <div class="col-xs-2">
                                                        <img class="nav-user-photo" src="assets/images/gallery/acsc_logo.jpeg" width="70" height="70" alt="College Logo" />
                                                    </div>
                                                    <div class="col-xs-10">
                                                        Jamner Taluka Education Society's<br>
                                                        <b>GITABAI DATTATRAY MAHAJAN ARTS, SHRI KESHARIMAL RAJMAL NAVLAKHA COMMERCE AND MANOHARSHETH DHARIWAL SCIENCE COLLEGE, JAMNER</b><br>
                                                        Tal. Jamner Dist. Jalgaon
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $all_counter_sum = 0;
                                            for ($index5 = 0; $index5 < count(array_keys($temp)); $index5++) {
                                                $collection_sum = 0;
                                                ?>
                                                <div class="clearfix">
                                                    <div class="table-header col-xs-10 col-xs-offset-1">
                                                        <b><?php echo array_keys($temp)[$index5]; ?> | Fees Collection Report : <?php echo $_GET['group'] ?> College (<?php echo date('d-M-Y', strtotime($_GET['date_from'])); ?>  &nbsp;to&nbsp; <?php echo date('d-M-Y', strtotime($_GET['date_to'])); ?>)</b>
                                                    </div>
                                                    <div class="row" >
                                                        <div class="col-xs-10 col-xs-offset-1" style="text-align: center">
                                                            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <tr class="row">
                                                                        <th class="col-xs-2" style="text-align: center">Code</th>
                                                                        <th class="col-xs-5" style="text-align: center">Title</th>
                                                                        <th class="col-xs-5" style="text-align: center">Collection</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php for ($index6 = 0; $index6 < count($temp[array_keys($temp)[$index5]]); $index6++) { ?>
                                                                        <tr class="row">
                                                                            <td class="col-xs-2"><?php echo (array_keys($temp[array_keys($temp)[$index5]])[$index6]); ?></td>
                                                                            <td class="col-xs-5"><?php echo $course_id_name[array_keys($temp[array_keys($temp)[$index5]])[$index6]]; ?></td>
                                                                            <td class="col-xs-5"><?php
                                                                                echo ($temp[array_keys($temp)[$index5]][array_keys($temp[array_keys($temp)[$index5]])[$index6]]);
                                                                                $collection_sum += ($temp[array_keys($temp)[$index5]][array_keys($temp[array_keys($temp)[$index5]])[$index6]]);
                                                                                ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    <tr class="row">
                                                                        <td class="col-xs-7" colspan="2" style="text-align: right;"><b>Total</b></td>
                                                                        <td class="col-xs-5"><b><?php
                                                                                echo $collection_sum;
                                                                                $all_counter_sum += $collection_sum;
                                                                                ?></b></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <hr class="invisible-row">
                                        <div class="table-header col-xs-10 col-xs-offset-1">
                                            <b>All counter Fees Collection Total : <u><?php echo $all_counter_sum; ?> </u></b>
                                        </div>
                                        <div class="col-xs-10 col-xs-offset-1" style="top: 30px;">
                                            <div class="text-center" style="float: right;">
                                                <br><b>PRINCIPAL</b><br>
                                                GITABAI DATTATRAY MAHAJAN ARTS, SHRI<br>
                                                KESHARIMAL RAJMAL NAVLAKHA
                                                <br>COMMERCE AND MANOHARSHETH DHARIWAL <br>SCIENCE COLLEGE, JAMNER
                                            </div>
                                        </div>
                                    <?php } ?>
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
                                                                        if (($('#date_range_from').val()) && ($('#date_range_to').val())) {
                                                                            window.location.replace("?date_from=" + $('#date_range_from').val() + "&date_to=" + $('#date_range_to').val() + "&group=" + $('#group').val());
                                                                        }
                                                                    }
                                                                    function selectDate() {
                                                                        if (($('#date_range_from').val()) && ($('#date_range_to').val())) {
                                                                            window.location.replace("?date_from=" + $('#date_range_from').val() + "&date_to=" + $('#date_range_to').val() + "&group=" + $('#group').val());
                                                                        }
                                                                    }
                                                                    jQuery(function ($) {
                                                                        //initiate dataTables plugin
                                                                        var myTable =
                                                                                $('#dynamic-table')
                                                                                //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                                                                                .DataTable({
                                                                                    bAutoWidth: false,
                                                                                    "paging": false,
                                                                                    "ordering": false,
                                                                                    "info": false,
                                                                                    "searching": false,
//                                                                    "aoColumns": [
//                                                                        {"bSortable": false},
//                                                                        null, null, null, null, null,
//                                                                        {"bSortable": false}
//                                                                    ],
                                                                                    "aaSorting": [],
                                                                                    //"bProcessing": true,
                                                                                    //"bServerSide": true,
                                                                                    //"sAjaxSource": "http://127.0.0.1/table.php"	,

                                                                                    //,
                                                                                    //"sScrollY": "200px",
                                                                                    //"bPaginate": false,

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
                                                                        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
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
                                                                            var th_checked = this.checked; //checkbox inside "TH" table header

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
                                                                            var th_checked = this.checked; //checkbox inside "TH" table header

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