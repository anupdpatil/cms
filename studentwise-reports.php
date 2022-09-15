<?php
session_start();
$user_data = [];
    include 'data_manipulator_config.php';
    $link = config();
$sql = "SELECT * from course";
$result = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($result, $user_data);
    }
} else {
    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
}
$st_date = date('Y-m-d H:i:s', strtotime($_GET['date']) + 1);
$end_date = date('Y-m-d H:i:s', strtotime($_GET['date']) + ((60 * 60 * 24) - 1));
if (isset($_GET['course']) && isset($_GET['date'])) {
    $sql = "SELECT ad.*,
        cr.fees_breakup, 
        inv.id as invoice_number, 
        inv.invoice_no, 
        inv.data as invoice_data, 
        inv.created_by as invoice_by,
        inv.created_date as invoice_date 
        from admission_form ad 
        JOIN invoices inv on inv.id = ad.invoice_id 
        JOIN course cr on cr.id = ad.class 
        WHERE ad.class = " . $_GET['course'] . " 
        AND ad.created_date between '" . $st_date . "' and '" . $end_date . "'";
    $data = [];
    if ($res = mysqli_query($link, $sql)) {
        while ($course_data = mysqli_fetch_assoc($res)) {
            array_push($data, $course_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }
}
if (!empty($data[0])) {
    $headers = [];
    $fees_arr = (json_decode($data[0]['fees_breakup']));
    for ($i = 0; $i < count($fees_arr); $i++) {
        array_push($headers, $fees_arr[$i]->header);
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
                            <li class="active"><?php // echo strtoupper(str_replace('_', ' ', $_GET['type']));                                                     ?></li>
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
                                <div class="row">
                                    <select name="course" id="course"  class="col-xs-10 col-sm-2" onchange="getData()">
                                        <option value=""> Please Select Course </option>
                                        <?php for ($index = 0; $index < count($result); $index++) { ?>
                                            <?php if ($result[$index]['id'] == $_GET['course']) { ?>
                                                <option selected="true" value="<?php echo $result[$index]['id']; ?>"><?php echo $result[$index]['course_name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $result[$index]['id']; ?>"><?php echo $result[$index]['course_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <div class="form-group">
                                        <label class="col-sm-1 control-label no-padding-right" for="form-field-1"> Record For: </label>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input id="date_range" type="date" <?php if (isset($_GET['date'])) { ?>value="<?php echo $_GET['date']; ?>" <?php } ?> name="date" onchange="selectDate()">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div class="table-header">
                                        Daily Report
                                    </div>
                                    <div class="col-sm-12" style="overflow-x: auto; height: 450px;">
                                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student Name</th>
                                                    <th>Invoice By</th>
                                                    <th>Invoice No.</th>
                                                    <th>Date</th>
                                                    <?php
                                                    if (!empty($headers)) {
                                                        for ($index1 = 0; $index1 < count($headers); $index1++) {
                                                            ?>
                                                            <th><?php echo $headers[$index1]; ?> </th>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <th>Total</th>
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
                                                            <td><?php echo $data[$index]['first_name'] . ' ' . $data[$index]['middle_name'] . ' ' . $data[$index]['last_name']; ?></td>
                                                            <td><?php echo $data[$index]['invoice_by']; ?></td>
                                                            <td><?php echo $data[$index]['invoice_no']; ?></td>
                                                            <td><?php echo date('d-M-Y', strtotime($data[$index]['invoice_date'])); ?></td>
                                                            <?php
//                                                            $f = [];
                                                            $f = json_decode($data[$index]['invoice_data']);
                                                            $total_of_row = 0;
                                                            for ($index1 = 0; $index1 < count($headers); $index1++) {
                                                                $key = str_replace(' ', '_', $headers[$index1]);
                                                                ?>
                                                                <td><?php
                                                                    if (property_exists($f, $key)) {
                                                                        echo $f->$key;
                                                                        $total_of_row += (int) $f->$key;
                                                                        $total_of_col[$index1] += (int) $f->$key;
                                                                    } else {
                                                                        echo '-';
                                                                    }
                                                                    ?> 
                                                                </td>
                                                            <?php } ?>
                                                            <td style="font-size: larger;"><?php
                                                                echo $total_of_row;
                                                                $total_of_total += $total_of_row;
                                                                ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td style="font-size: x-large;">Total</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <?php for ($index2 = 0; $index2 < count($headers); $index2++) { ?>
                                                            <td style="font-size: larger;"> <?php echo $total_of_col[$index2]; ?></td>
                                                        <?php }
                                                        ?>
                                                        <td style="font-size: x-large;"><?php echo $total_of_total; ?></td>
                                                    </tr>
                                                <?php }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--<button class="btn btn-primary" id="exportButton">Export To Excel</button>-->
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
                                                        if ($('#date_range').val())
                                                            window.location.replace("?course=" + $('#course').val() + "&date=" + $('#date_range').val());
                                                    }
                                                    function selectDate() {
                                                        if ($('#course').val())
                                                            window.location.replace("?course=" + $('#course').val() + "&date=" + $('#date_range').val());
                                                    }
                                                    function exportTableToExcel(tableID, filename = '') {
                                                        var downloadLink;
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
                                                        $('#exportButton').click(function () {
                                                            exportTableToExcel('dynamic-table', 'tmpFile');
                                                        });
                                                        var myTable =
                                                                $('#dynamic-table')
                                                                .wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
                                                                .DataTable({
                                                                    bAutoWidth: false,
                                                                    "aoColumns": [],
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
