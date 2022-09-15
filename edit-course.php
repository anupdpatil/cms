<?php
session_start();
include 'data_manipulator_config.php';
$link = config();

if (isset($_GET['id'])) {
    $link = config();
    $sql = "SELECT * from course ";
    $result = [];
    $result_arr = [];
    if ($res = mysqli_query($link, $sql)) {
        while ($user_data = mysqli_fetch_assoc($res)) {
            array_push($result_arr, $user_data);
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    for ($i = 0; $i < count($result_arr); $i++) {
        if ($result_arr[$i]['id'] == $_GET['id']) {
            array_push($result, $result_arr[$i]);
        }
    }
    mysqli_close($link);
}
if (!empty($_POST)) {
    $link = config();
    $course_name = $_POST['course_name'];
    $course_group = $_POST['course_group'];
    $fees = 0;
    $head_arr = json_decode($_POST['header']);
    for ($index = 0; $index < count($head_arr); $index++) {
        $fees+=$head_arr[$index]->value;
    }
    $start_year = $_POST['start_year'];
    $start_year = $_POST['start_year'];
    $fees_breakup = $_POST['header'];
    $admission_capacity = $_POST['admission_capacity'];
    $next_course = $_POST['next_course'];
    $next_course_admission_open = $_POST['next_course_admission_open'];
    $date = date('Y-m-d H:i:s');
    $sql = "UPDATE course SET course_name = '$course_name', next_course_admission_open = '$next_course_admission_open', next_course = '$next_course', course_group = '$course_group', fees = '$fees', start_year = '$start_year', admission_capacity='$admission_capacity',modified_date='$date',fees_breakup='$fees_breakup' WHERE id =" . $_GET['id'];
    if (mysqli_query($link, $sql)) {
        $url = $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_NAME'] == 'localhost')
            header('Location: http://localhost/acsc/courses.php');
        else
            header('Location: http://' . $url . '/courses.php');
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    mysqli_close($link);
} else {
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
                                <li class="active">Course</li>
                            </ul><!-- /.breadcrumb -->
                        </div>

                        <div class="page-content">
                            <div class="page-header">
                                <h1>
                                    Edit Course Form
                                </h1>
                            </div><!-- /.page-header -->
                            <div class="alert alert-danger">
                                <strong>Warning!</strong>
                                Change in header will affect your DCR report and it can result in mismatch in invoices.
                                <br />
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="col-xs-7">
                                        <form action="" method="post" class="form-horizontal" role="form" id="invoice_form">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Course Name </label>
                                                <div class="col-sm-9">
                                                    <input required name="course_name" type="text" id="course_name" placeholder="Name of Course" class="col-xs-10 col-sm-9" value="<?php echo $result[0]['course_name']; ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Start Year </label>
                                                <div class="col-sm-9">
                                                    <input required name="start_year" type="number" id="start_year" placeholder="Course Start Year" class="col-xs-10 col-sm-9" value="<?php echo $result[0]['start_year']; ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Course Fees </label>
                                                <?PHP
                                                $course_fee_sum = 0;
                                                $head_arr = json_decode($result[0]['fees_breakup']);
                                                for ($index = 0; $index < count($head_arr); $index++) {
                                                    $course_fee_sum+=$head_arr[$index]->value;
                                                }
                                                ?>

                                                <div class="col-sm-9">
                                                    <input readonly="true" name="course_fees" type="number" id="course_fees" placeholder="Fees Of Course" class="col-xs-10 col-sm-9" value="<?php echo $course_fee_sum; ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Admission Capacity </label>
                                                <div class="col-sm-9">
                                                    <input required name="admission_capacity" type="number" id="admission_capacity" placeholder="Student Intake For Course" class="col-xs-10 col-sm-9" value="<?php echo $result[0]['admission_capacity']; ?>"/>
                                                    <input hidden name="header" type="text" id="header" />
                                                    <input hidden name="available_headers" type="text" id="available_headers" value="<?php echo count(json_decode($result[0]['fees_breakup'])); ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label no-padding-right" for="course_group"> Course Group </label>
                                                <div class="col-sm-9">
                                                    <select required name="course_group" id="course_group"  class="col-xs-10 col-sm-9">
                                                        <option value="">Please Select Course Group</option>
                                                        <option <?php if ($result[0]['course_group'] == 'NG') { ?> selected="true" <?php } ?>value="NG">NG</option>
                                                        <option <?php if ($result[0]['course_group'] == 'JR') { ?> selected="true" <?php } ?>value="JR">JR</option>
                                                        <option <?php if ($result[0]['course_group'] == 'DC') { ?> selected="true" <?php } ?>value="DC">DC</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php if (($_SESSION['user'] == 'Anup Patil')) { ?>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="course_group"> Next Course </label>
                                                    <div class="col-sm-9">
                                                        <select required name="next_course" id="next_course"  class="col-xs-10 col-sm-9">
                                                            <option value="0">Please Select Next Course</option>
                                                            <?php
                                                            for ($i = 0; $i < count($result_arr); $i++) {
                                                                if (($result_arr[$i]['id']) != ($_GET['id'])) {
                                                                    ?>
                                                                    <option <?php if ($result[0]['next_course'] == $result_arr[$i]['id']) { ?> selected="true" <?php } ?>value="<?php echo $result_arr[$i]['id']; ?>"><?php echo $result_arr[$i]['course_name']; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="course_group"> Next Year Admission open </label>
                                                    <div class="col-sm-9">
                                                        <select required name="next_course_admission_open" id="next_course_admission_open"  class="col-xs-10 col-sm-9">
                                                            <option <?php if ($result[0]['next_course_admission_open'] == 0) { ?> selected="true" <?php } ?>value="<?php echo '0'; ?>">No</option>
                                                            <option <?php if ($result[0]['next_course_admission_open'] == 1) { ?> selected="true" <?php } ?>value="<?php echo '1'; ?>">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="space-4"></div>
                                            <?php if (($_SESSION['user'] == 'Anup Patil') || ($_SESSION['user'] == 'Jayram Chaudhari')) { ?>
                                                <div class="clearfix form-actions">
                                                    <div class="col-xs-offset-3 col-xs-9">
                                                        <button class="btn btn-info" type="button" id="submitBtn">
                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                            Submit
                                                        </button>
                                                        &nbsp; &nbsp; &nbsp;
                                                        <button class="btn" type="reset">
                                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                                            Reset
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                    <div class="col-xs-3">
                                        Add New Fee Component
                                        <input type='button' value='Add New Header' id='addButton'>
                                        <input type='button' value='Remove Last Header' id='removeButton'>
                                        <div id='TextBoxesGroup'>
                                            <div id="TextBoxDiv">
                                            </div>
                                            <?php
                                            $header_arr = json_decode($result[0]['fees_breakup']);
                                            for ($index = 0; $index < count($header_arr); $index++) {
                                                ?>
                                                <div id="TextBoxDiv<?php echo $index + 1; ?>">
                                                    <div class="form-group">
                                                        <input type="text" id="header<?php echo $index; ?>" placeholder="Header" class="col-xs-10 col-sm-7" value="<?php echo $header_arr[$index]->header; ?>" />
                                                        <input type="number" id="value<?php echo $index; ?>" placeholder="Value" class="col-sm-offset-1 col-xs-10 col-sm-4" value="<?php echo $header_arr[$index]->value; ?>" />
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
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

            <!--[if lte IE 8]>
              <script src="assets/js/excanvas.min.js"></script>
            <![endif]-->
            <script src="assets/js/jquery-ui.custom.min.js"></script>
            <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
            <script src="assets/js/jquery.easypiechart.min.js"></script>
            <script src="assets/js/jquery.sparkline.index.min.js"></script>
            <script src="assets/js/jquery.flot.min.js"></script>
            <script src="assets/js/jquery.flot.pie.min.js"></script>
            <script src="assets/js/jquery.flot.resize.min.js"></script>
            <script src="assets/js/bootstrap-datepicker.min.js"></script>

            <!-- ace scripts -->
            <script src="assets/js/ace-elements.min.js"></script>
            <script src="assets/js/ace.min.js"></script>

            <!-- inline scripts related to this page -->
            <script type="text/javascript">
                var counter = ($('#available_headers').val()) ? $('#available_headers').val() : 1;
                $("#addButton").click(function () {
                    var newTextBoxDiv = $(document.createElement('div'))
                            .attr("id", 'TextBoxDiv' + counter);

                    newTextBoxDiv.after().html(
                            '<div class="form-group">' +
                            '<input required "textbox' + counter + '" type="text" id="header' + counter + '" placeholder="Header" class="col-xs-10 col-sm-7" />' +
                            '<input required "textbox' + counter + '" type="number" id="value' + counter + '" placeholder="Value" class="col-sm-offset-1 col-xs-10 col-sm-4" />' +
                            '</div>');
                    newTextBoxDiv.appendTo("#TextBoxesGroup");
                    counter++;
                });

                $("#removeButton").click(function () {
                    if (counter == 1) {
                        alert("Can not delete last Header");
                        return false;
                    }
                    $("#TextBoxDiv" + counter).remove();
                    counter--;
                });

                $("#getButtonValue").click(function () {
                });
                jQuery(function ($) {
                    $('#invoice_form').on('keyup keypress', function (e) {
                        var keyCode = e.keyCode || e.which;
                        if (keyCode === 13) {
                            e.preventDefault();
                            return false;
                        }
                    });
                    $("#submitBtn").click(function (e) {
                        var headers_arr = [];
                        for (i = 0; i < counter; i++) {
                            var tempObj = {header: $('#header' + i).val(), value: $('#value' + i).val()};
                            headers_arr.push(tempObj);
                        }
                        if (headers_arr.length)
                            $('#header').val(JSON.stringify(headers_arr));
                        else
                            $('#header').val('');

                        e.preventDefault();
                        if (window.confirm("Are you sure, you want to submit the form?")) {
                            $("#invoice_form").submit();
                        }
                    });
                    $('.easy-pie-chart.percentage').each(function () {
                        var $box = $(this).closest('.infobox');
                        var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
                        var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
                        var size = parseInt($(this).data('size')) || 50;
                        $(this).easyPieChart({
                            barColor: barColor,
                            trackColor: trackColor,
                            scaleColor: false,
                            lineCap: 'butt',
                            lineWidth: parseInt(size / 10),
                            animate: ace.vars['old_ie'] ? false : 1000,
                            size: size
                        });
                    })

                    $('.sparkline').each(function () {
                        var $box = $(this).closest('.infobox');
                        var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
                        $(this).sparkline('html',
                                {
                                    tagValuesAttribute: 'data-values',
                                    type: 'bar',
                                    barColor: barColor,
                                    chartRangeMin: $(this).data('min') || 0
                                });
                    });
                    $('.date-picker').datepicker({
                        autoclose: true,
                        todayHighlight: true
                    })
                            //show datepicker when clicking on the icon
                            .next().on(ace.click_event, function () {
                        $(this).prev().focus();
                    });

                    //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
                    //but sometimes it brings up errors with normal resize event handlers
                    $.resize.throttleWindow = false;

                    var placeholder = $('#piechart-placeholder').css({'width': '90%', 'min-height': '150px'});
                    var data = [
                        {label: "social networks", data: 38.7, color: "#68BC31"},
                        {label: "search engines", data: 24.5, color: "#2091CF"},
                        {label: "ad campaigns", data: 8.2, color: "#AF4E96"},
                        {label: "direct traffic", data: 18.6, color: "#DA5430"},
                        {label: "other", data: 10, color: "#FEE074"}
                    ]
                    function drawPieChart(placeholder, data, position) {
                        $.plot(placeholder, data, {
                            series: {
                                pie: {
                                    show: true,
                                    tilt: 0.8,
                                    highlight: {
                                        opacity: 0.25
                                    },
                                    stroke: {
                                        color: '#fff',
                                        width: 2
                                    },
                                    startAngle: 2
                                }
                            },
                            legend: {
                                show: true,
                                position: position || "ne",
                                labelBoxBorderColor: null,
                                margin: [-30, 15]
                            }
                            ,
                            grid: {
                                hoverable: true,
                                clickable: true
                            }
                        })
                    }
                    drawPieChart(placeholder, data);

                    /**
                     we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
                     so that's not needed actually.
                     */
                    placeholder.data('chart', data);
                    placeholder.data('draw', drawPieChart);


                    //pie chart tooltip example
                    var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
                    var previousPoint = null;

                    placeholder.on('plothover', function (event, pos, item) {
                        if (item) {
                            if (previousPoint != item.seriesIndex) {
                                previousPoint = item.seriesIndex;
                                var tip = item.series['label'] + " : " + item.series['percent'] + '%';
                                $tooltip.show().children(0).text(tip);
                            }
                            $tooltip.css({top: pos.pageY + 10, left: pos.pageX + 10});
                        } else {
                            $tooltip.hide();
                            previousPoint = null;
                        }

                    });

                    /////////////////////////////////////
                    $(document).one('ajaxloadstart.page', function (e) {
                        $tooltip.remove();
                    });




                    var d1 = [];
                    for (var i = 0; i < Math.PI * 2; i += 0.5) {
                        d1.push([i, Math.sin(i)]);
                    }

                    var d2 = [];
                    for (var i = 0; i < Math.PI * 2; i += 0.5) {
                        d2.push([i, Math.cos(i)]);
                    }

                    var d3 = [];
                    for (var i = 0; i < Math.PI * 2; i += 0.2) {
                        d3.push([i, Math.tan(i)]);
                    }


                    var sales_charts = $('#sales-charts').css({'width': '100%', 'height': '220px'});
                    $.plot("#sales-charts", [
                        {label: "Domains", data: d1},
                        {label: "Hosting", data: d2},
                        {label: "Services", data: d3}
                    ], {
                        hoverable: true,
                        shadowSize: 0,
                        series: {
                            lines: {show: true},
                            points: {show: true}
                        },
                        xaxis: {
                            tickLength: 0
                        },
                        yaxis: {
                            ticks: 10,
                            min: -2,
                            max: 2,
                            tickDecimals: 3
                        },
                        grid: {
                            backgroundColor: {colors: ["#fff", "#fff"]},
                            borderWidth: 1,
                            borderColor: '#555'
                        }
                    });


                    $('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
                    function tooltip_placement(context, source) {
                        var $source = $(source);
                        var $parent = $source.closest('.tab-content')
                        var off1 = $parent.offset();
                        var w1 = $parent.width();

                        var off2 = $source.offset();
                        //var w2 = $source.width();

                        if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2))
                            return 'right';
                        return 'left';
                    }


                    $('.dialogs,.comments').ace_scroll({
                        size: 300
                    });


                    //Android's default browser somehow is confused when tapping on label which will lead to dragging the task
                    //so disable dragging when clicking on label
                    var agent = navigator.userAgent.toLowerCase();
                    if (ace.vars['touch'] && ace.vars['android']) {
                        $('#tasks').on('touchstart', function (e) {
                            var li = $(e.target).closest('#tasks li');
                            if (li.length == 0)
                                return;
                            var label = li.find('label.inline').get(0);
                            if (label == e.target || $.contains(label, e.target))
                                e.stopImmediatePropagation();
                        });
                    }

                    $('#tasks').sortable({
                        opacity: 0.8,
                        revert: true,
                        forceHelperSize: true,
                        placeholder: 'draggable-placeholder',
                        forcePlaceholderSize: true,
                        tolerance: 'pointer',
                        stop: function (event, ui) {
                            //just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
                            $(ui.item).css('z-index', 'auto');
                        }
                    }
                    );
                    $('#tasks').disableSelection();
                    $('#tasks input:checkbox').removeAttr('checked').on('click', function () {
                        if (this.checked)
                            $(this).closest('li').addClass('selected');
                        else
                            $(this).closest('li').removeClass('selected');
                    });


                    //show the dropdowns on top or bottom depending on window height and menu position
                    $('#task-tab .dropdown-hover').on('mouseenter', function (e) {
                        var offset = $(this).offset();

                        var $w = $(window)
                        if (offset.top > $w.scrollTop() + $w.innerHeight() - 100)
                            $(this).addClass('dropup');
                        else
                            $(this).removeClass('dropup');
                    });

                })
            </script>
        </body>
    </html>
    <?php
}
?>