<?php
//die('Page is under Maintainance Mode');
session_start();
$user_data = [];
include 'data_manipulator_config.php';
$link = config();
$sql = "SELECT * from course";
$course = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($course, $user_data);
    }
} else {
    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
}
$students = [];
if (isset($_GET['course'])) {
    $link = config();
    $sql = "SELECT * from admission_form where class ='" . $_GET['course'] . "'";
    if ($res = mysqli_query($link, $sql)) {
        while ($students_data = mysqli_fetch_assoc($res)) {
            array_push($students, $students_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
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
                    <div class="breadcrumbs breadcrumbs-fixed " id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <i class="ace-icon fa fa-home home-icon"></i>
                                <a href="#">Home</a>
                            </li>

                            <li>
                                <a href="#">Admission Report</a>
                            </li>
                            <li class="active"><?php // echo strtoupper(str_replace('_', ' ', $_GET['type']));                                                                                              ?></li>
                        </ul>
                    </div>
                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> Course Group: </label>
                                        <select name="course" id="course" onchange="getData()">
                                            <option value="">--- Course ---</option>
                                            <?php for ($index = 0; $index < count($course); $index++) { ?>
                                                <?php if ($course[$index]['id'] == $_GET['course']) { ?>
                                                    <option selected="true" value="<?php echo $course[$index]['id']; ?>"><?php echo $course[$index]['course_name']; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $course[$index]['id']; ?>"><?php echo $course[$index]['course_name']; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="table-header">
                                        Student List
                                    </div>
                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Roll No</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($students)) {
                                                for ($index1 = 0; $index1 < count($students); $index1++) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index1 + 1; ?></td>
                                                        <td><a href="make-repayment.php?form=<?php echo $students[$index1]['id']; ?>&class=<?php echo $_GET['course']; ?>"><?php echo $students[$index1]['first_name'] . ' ' . $students[$index1]['middle_name'] . ' ' . $students[$index1]['last_name']; ?></a> </td>
                                                        <td><?php echo $students[$index1]['student_roll_number']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
                                                if ($('#course').val())
                                                    window.location.replace("?course=" + $('#course').val());
                                            }
                                            jQuery(function ($) {
                                                //initiate dataTables plugin
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
