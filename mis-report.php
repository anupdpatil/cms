<?php
session_start();
$user_data = [];
include 'data_manipulator_config.php';
$link = config();
$category = ['OBC', 'SC', 'ST', 'SBC', 'GENERAL', 'PTWF', 'HSTWF', 'VJ_NT_A', 'NT_B', 'NT_C', 'NT_D', 'E_S_B_C', 'Freedom_fighter'];
$sql = "select
        c.course_name as Course, ad.gender as Gender,
        count(case when ad.category='OBC' then 1 end) as OBC,
        count(case when ad.category='SC' then 1 end) as SC,
        count(case when ad.category='ST' then 1 end) as ST,
        count(case when ad.category='SBC' then 1 end) as SBC,
        count(case when ad.category='GENERAL' then 1 end) as GENERAL,
        count(case when ad.category='PTWF' then 1 end) as PTWF,
        count(case when ad.category='HSTWF' then 1 end) as HSTWF,
        count(case when ad.category='VJ NT (A)' then 1 end) as VJ_NT_A,
        count(case when ad.category='NT B' then 1 end) as NT_B,
        count(case when ad.category='NT C' then 1 end) as NT_C,
        count(case when ad.category='NT D' then 1 end) as NT_D,
        count(case when ad.category='E.S.B.C.' then 1 end) as E_S_B_C,
        count(case when ad.category='Freedom fighter' then 1 end) as Freedom_fighter,
        count(*) as Total
        from admission_form ad JOIN course c on c.id=ad.class
        group by c.course_name,ad.gender";

$result = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($result, $user_data);
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
$cr = [];
for ($index1 = 0; $index1 < count($result); $index1++) {
    array_push($cr, $result[$index1]['Course']);
}
$cr = array_values(array_unique($cr));
$final_data = [];
for ($index2 = 0; $index2 < count($cr); $index2++) {
    $temp = [];
    for ($index1 = 0; $index1 < count($result); $index1++) {
        if ($cr[$index2] == $result[$index1]['Course']) {
            array_push($temp, $result[$index1]);
        }
        $final_data[$cr[$index2]] = $temp;
    }
}
$table_header = [];
if (!empty($result)) {
    $table_header = array_keys($result[0]);
    unset($table_header[1]);
    unset($table_header[15]);
}
$table_header = array_values($table_header);
$cnt = count($table_header) - 1;
for ($i = 0; $i < $cnt; $i++) {
    array_push($table_header, 'T');
    array_push($table_header, 'F');
    array_push($table_header, 'M');
}
$inserted_value = 'Total';
$position = 1;
array_splice($table_header, $position, 0, $inserted_value);
$inserted_value = 'Female';
$position = 2;
array_splice($table_header, $position, 0, $inserted_value);
$inserted_value = 'Male';
$position = 3;
array_splice($table_header, $position, 0, $inserted_value);

$row_wise_table_data = [];
for ($index2 = 0; $index2 < count($cr); $index2++) {
    $table_data = [];
    array_push($table_data, $cr[$index2]);
    if (count($final_data[$cr[$index2]]) == 1) {
        array_push($table_data, $final_data[$cr[$index2]][0]['Total']);
        array_push($table_data, $final_data[$cr[$index2]][0]['Total']);
        array_push($table_data, '0');
    } else if (count($final_data[$cr[$index2]]) == 2) {
        array_push($table_data, $final_data[$cr[$index2]][0]['Total'] + $final_data[$cr[$index2]][1]['Total']);
        array_push($table_data, $final_data[$cr[$index2]][0]['Total']);
        array_push($table_data, $final_data[$cr[$index2]][1]['Total']);
    } else if (count($final_data[$cr[$index2]]) == 3) {
        array_push($table_data, $final_data[$cr[$index2]][0]['Total'] + $final_data[$cr[$index2]][1]['Total'] + $final_data[$cr[$index2]][2]['Total']);
        array_push($table_data, $final_data[$cr[$index2]][1]['Total']);
        array_push($table_data, $final_data[$cr[$index2]][2]['Total']);
    }
    for ($i = 0; $i < count($category); $i++) {
        if (count($final_data[$cr[$index2]]) == 1) {
            array_push($table_data, $final_data[$cr[$index2]][0][$category[$i]]);
            array_push($table_data, '0');
            array_push($table_data, '0');
        } else if (count($final_data[$cr[$index2]]) == 2) {
            array_push($table_data, ((int) $final_data[$cr[$index2]][0][$category[$i]] + (int) $final_data[$cr[$index2]][1][$category[$i]]));
            array_push($table_data, $final_data[$cr[$index2]][0][$category[$i]]);
            array_push($table_data, $final_data[$cr[$index2]][1][$category[$i]]);
        } else if (count($final_data[$cr[$index2]]) == 3) {
            array_push($table_data, ((int) $final_data[$cr[$index2]][0][$category[$i]] + (int) $final_data[$cr[$index2]][1][$category[$i]] + (int) $final_data[$cr[$index2]][2][$category[$i]]));
            array_push($table_data, $final_data[$cr[$index2]][1][$category[$i]]);
            array_push($table_data, $final_data[$cr[$index2]][2][$category[$i]]);
        }
    }
    array_push($row_wise_table_data, $table_data);
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
                        <ul class="breadcrumb invisible-row">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <a href="#">Home</a>
                            </li>
                            <li class="active">MIS Report</li>
                        </ul>
                    </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
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
                                <br>
                                <br>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="table-header">
                                            MIS Report
                                            <div class="printing invisible-row pull-right">
                                                <button id="print" class="btn btn-default btn-sm btn-outline" onclick="print()"> <span><i class="fa fa-print"></i> Print</span> </button>
                                            </div>
                                        </div>
                                        <div>
                                            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <?php for ($i = 0; $i < count($table_header); $i++) { ?>
                                                            <?php if ($i < 4) { ?>
                                                                <th><?php echo $table_header[$i]; ?></th>
                                                            <?php } else if ($i < 17) { ?>
                                                                <th><p><u><?php echo str_replace("_", " ", $table_header[$i]); ?></u></p>
                                                                    <p><span class="text-right">Total</span>
                                                                        <span class="text-right">Female</span>
                                                                        <span class="text-right">Male</span></p>
                                                                </th>
                                                                <?php // } else if ($i == 17) {  ?>
                                                            <!--</tr><tr><th><?php // echo $table_header[$i];              ?></th>-->
                                                                <?php // } else {  ?>
                                                                <!--<th><?php // echo $table_header[$i];            ?></th>-->
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for ($i = 0; $i < count($row_wise_table_data); $i++) { ?>
                                                        <tr>
                                                            <?php for ($i1 = 0; $i1 < count($row_wise_table_data[$i]); $i1++) { ?>
                                                                <td class="text-center">
                                                                    <!--<u><?php // echo $i1;           ?></u>-->
                                                                    <?php
                                                                    if ($i1 < 4) {
                                                                        echo $row_wise_table_data[$i][$i1];
                                                                        ?>
                                                                        <br><?php echo ''; ?>
                                                                        <br><?php echo ''; ?>
                                                                        <?php
                                                                    } else {
                                                                        echo $row_wise_table_data[$i][$i1];
                                                                        $i1++;
                                                                        ?>
                                                                        <br> <?php
                                                                        echo $row_wise_table_data[$i][$i1];
                                                                        $i1++;
                                                                        ?>
                                                                        <br> <?php echo $row_wise_table_data[$i][$i1]; ?>
                                                                    <?php } ?>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php } ?>
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
                                                                //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                                                                .DataTable({
                                                                    bAutoWidth: false,
                                                                    ordering: false,
//                                                                "aoColumns": [
//                                                                    {"bSortable": false},
//                                                                    null, null, null, null, null, null, null,
//                                                                    {"bSortable": false}
//                                                                ],
//                                                            "bSorting": false,
                                                                    //"bProcessing": true,
                                                                    //"bServerSide": true,
                                                                    //"sAjaxSource": "http://127.0.0.1/table.php"	,

                                                                    //,
                                                                    //"sScrollY": "200px",
                                                                    "bPaginate": false,
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

                                                        new $.fn.dataTable.Buttons(myTable, {
                                                            buttons: [
                                                                {
                                                                    "extend": "colvis",
                                                                    "text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
                                                                    "className": "btn btn-white btn-primary btn-bold",
                                                                    columns: ':not(:first):not(:last)'
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
//                                {
//                                    "extend": "print",
//                                    "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
//                                    "className": "btn btn-white btn-primary btn-bold",
//                                    autoPrint: false,
//                                    message: 'This print was produced using the Print button for DataTables'
//                                }
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
