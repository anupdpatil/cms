<?php
session_start();
$user_data = [];
    include 'data_manipulator_config.php';
    $link = config();

if (!isset($_GET['type'])) {
    if ($_SERVER['SERVER_NAME'] == 'localhost')
        header('Location: http://localhost/acsc');
    else
        header('Location: https://' . $_SERVER['SERVER_NAME']);
}

if ($_GET['type'] == 'counter_wise') {
    $sql = "SELECT * from invoices";
    $result = [];
    if ($res = mysqli_query($link, $sql)) {
        while ($user_data = mysqli_fetch_assoc($res)) {
            array_push($result, $user_data);
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
} elseif ($_GET['type'] == 'header_wise') {
    $courses = array(1, 2);
    $course_list = implode(",", $courses);
    $course_wise_sql = "SELECT fees_breakup from course WHERE id IN ($course_list)";
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
    }
    $all_headers = array_values(array_unique($all_headers));

    $sql_new = "SELECT i.data "
            . "from invoices i "
            . "join admission_form af on af.invoice_id=i.id AND af.class IN ($course_list) "
            . "WHERE i.created_date between '" . date('Y-m-d', strtotime("-1 days")) . "' and '" . date('Y-m-d', strtotime("+1 days")) . "'";
    $data = [];
    if ($res_temp = mysqli_query($link, $sql_new)) {
        while ($user_data = mysqli_fetch_assoc($res_temp)) {
            array_push($data, $user_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql_new. " . mysqli_error($link));
    }
    $header_with_values = [];
    for ($i1 = 0; $i1 < count($all_headers); $i1++) {
        $prop = str_replace(' ', '_', $all_headers[$i1]);
        $header_with_values[$prop] = [];
        for ($i2 = 0; $i2 < count($data); $i2++) {
            $temp_obj = (json_decode($data[$i2]['data']));
            if (property_exists($temp_obj, $prop))
                array_push($header_with_values[$prop], json_decode($data[$i2]['data'])->$prop);
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
            <?php include 'test.php';?>
            <div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs breadcrumbs-fixed " id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <a href="#">Home</a>
                            </li>

                            <li>
                                <a href="#">Admission Report</a>
                            </li>
                            <li class="active"><?php echo strtoupper(str_replace('_', ' ', $_GET['type'])); ?></li>
                        </ul>

                        <div class="nav-search" id="nav-search">
                            <form class="form-search">
                                <span class="input-icon">
                                    <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                                    <i class="ace-icon fa fa-search nav-search-icon"></i>
                                </span>
                            </form>
                        </div><!-- /.nav-search -->
                    </div>

                    <div class="page-content">
                        <div class="ace-settings-container" id="ace-settings-container">
                            <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                                <i class="ace-icon fa fa-cog bigger-130"></i>
                            </div>

                            <div class="ace-settings-box clearfix" id="ace-settings-box">
                                <div class="pull-left width-50">
                                    <div class="ace-settings-item">
                                        <div class="pull-left">
                                            <select id="skin-colorpicker" class="hide">
                                                <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                                                <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                                <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                                <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                            </select>
                                        </div>
                                        <span>&nbsp; Choose Skin</span>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2 " id="ace-settings-navbar" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2 " id="ace-settings-sidebar" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2 " id="ace-settings-breadcrumbs" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2 " id="ace-settings-add-container" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-add-container">
                                            Inside
                                            <b>.container</b>
                                        </label>
                                    </div>
                                </div><!-- /.pull-left -->

                                <div class="pull-left width-50">
                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                                    </div>

                                    <div class="ace-settings-item">
                                        <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" autocomplete="off" />
                                        <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                                    </div>
                                </div><!-- /.pull-left -->
                            </div><!-- /.ace-settings-box -->
                        </div><!-- /.ace-settings-container -->

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <div class="row">
                                    <?php if ($_GET['type'] == 'counter_wise') { ?>
                                        <div class="col-xs-12">
                                            <div class="clearfix">
                                                <div class="pull-right tableTools-container"></div>
                                            </div>
                                            <div class="table-header">
                                                User Wise Report
                                            </div>
                                            <div>
                                                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Created By</th>
                                                            <th>Receipt No</th>
                                                            <th class="hidden-480">Date</th>
                                                            <th class="hidden-480">No of Downloads</th>
                                                            <th class="hidden-480">Total Fees</th>
                                                            <th class="hidden-480">Details</th>
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
                                                                    <?php echo $result[$index]['created_by']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $result[$index]['invoice_no']; ?>
                                                                </td>
                                                                <td class="hidden-480">
                                                                    <?php echo date($result[$index]['created_date']); ?>
                                                                </td>
                                                                <td class="hidden-480">
                                                                    <?php echo $result[$index]['no_of_downloads']; ?>
                                                                </td>
                                                                <td class="hidden-480">
                                                                    <?php
                                                                    $keys = array_keys(json_decode($result[$index]['data'], true));
                                                                    $character = json_decode($result[$index]['data']);
                                                                    $total = 0;
                                                                    for ($i = 0; $i < count($keys); $i++) {
                                                                        $total += $character->$keys[$i];
                                                                    }
                                                                    echo $total;
                                                                    ?>
                                                                </td>
                                                                <td class="hidden-480">
                                                                    <?php echo $result[$index]['id']; ?>
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } elseif ($_GET['type'] == 'header_wise') { ?>
                                        <div class="col-xs-12">
                                            <div class="clearfix">
                                                <div class="pull-right tableTools-container"></div>
                                            </div>
                                            <div class="table-header">
                                                Header Wise Report
                                            </div>
                                            <div>
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#">Jr College</a></li>
                                                    <li><a href="#">Sr College</a></li>
                                                    <li><a href="#">Graduation</a></li>
                                                    <li><a href="#">Non Grant Courses</a></li>
                                                </ul>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="clearfix">
                                                        <div class="pull-right tableTools-container"></div>
                                                    </div>
                                                    <div class="table-header">
                                                        Report for date: <?php echo date('d-M-Y'); ?>
                                                    </div>
                                                    <div>
                                                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Header</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <?php
                                                                $total_amount = 0;
                                                                for ($j1 = 0; $j1 < count($all_headers); $j1++) {
                                                                    $total_amount += array_sum($header_with_values[$prop]);
                                                                    $prop = str_replace(' ', '_', $all_headers[$j1]);
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $j1 + 1; ?></td>
                                                                        <td><?php echo str_replace('_', ' ', $prop); ?></td>
                                                                        <td><?php echo array_sum($header_with_values[$prop]); ?></td>
                                                                    </tr>
                                                                <?php }
                                                                ?>
                                                                <tr style="background: lightgray;">
                                                                    <td></td>
                                                                    <td><b>Total</b></td>
                                                                    <td><b><?php echo $total_amount; ?></b></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
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
                    jQuery(function ($) {
                        //initiate dataTables plugin
                        var myTable =
                                $('#dynamic-table')
                                //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                                .DataTable({
                                    bAutoWidth: false,
                                    "aoColumns": [
                                        {"bSortable": false},
                                        null, null, null, null, null,
                                        {"bSortable": false}
                                    ],
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
                                {
                                    "extend": "print",
                                    "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
                                    "className": "btn btn-white btn-primary btn-bold",
                                    autoPrint: true,
                                    message: 'This print was produced using the Print button for DataTables'
//                                    message: 'Daily '.$('.table-header').html()
                                }
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
