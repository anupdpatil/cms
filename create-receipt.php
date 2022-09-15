<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo '<script>alert("Session destroyed, Please refresh the page.")</script>';
    $url = $_SERVER['SERVER_NAME'];
    if ($_SERVER['SERVER_NAME'] == 'localhost')
        header('Location: http://localhost/acsc/');
    else
        header('Location: https://' . $url);
    die;
}
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

if (!empty($_POST)) {
    $link = config();
    $sql = "SELECT COUNT( id ) as cnt
            FROM fees WHERE created_by = '" . $_SESSION['user'] . "'";
    $cr = [];
    if ($res = mysqli_query($link, $sql)) {
        while ($cr_data = mysqli_fetch_assoc($res)) {
            array_push($cr, $cr_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }

    $temp_cnt = $cr[0]['cnt'] + 1;
    $invoice_number = 'C' . $_SESSION['id'] . '-' . 'OTR' . '-' . date('Y') . '-' . $temp_cnt;
    $o_data = $_POST;
    $data = json_encode($_POST);
    $created_by = $_SESSION['user'];
    $no_of_downloads = 1;
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO invoices (invoice_no,data,created_by,no_of_downloads, created_date) VALUES ("
            . "'$invoice_number',"
            . "'$data',"
            . "'$created_by',"
            . "'$no_of_downloads',"
            . "'$date');";
    if (mysqli_query($link, $sql)) {
        $_POST = $o_data;
        $invoice_id = $link->insert_id;
        $course_id = $_POST['course_id'];
        $student_name = $_POST['student_name'];
        $fee_type = $_POST['fee_type_value'];
        $created_by = $_SESSION['user'];
        $new_sql = "INSERT INTO fees (invoice_id,course_id,student_name,fee_type, created_by,created_date,modified_date) VALUES ("
                . "'$invoice_id',"
                . "'$course_id',"
                . "'$student_name',"
                . "'$fee_type',"
                . "'$created_by',"
                . "'$date',"
                . "'$date')";
        if (mysqli_query($link, $new_sql)) {
            $fee_id = $link->insert_id;
            $url = $_SERVER['SERVER_NAME'];
            if ($_SERVER['SERVER_NAME'] == 'localhost')
                header('Location: http://localhost/acsc/receipt.php?invoice_id=' . $invoice_id . '&id=' . $fee_id);
            else
                header('Location: https://' . $url . "/receipt.php?invoice_id=" . $invoice_id . '&id=' . $fee_id);
        } else {
            die("ERROR: Could not able to execute $new_sql. " . mysqli_error($link));
        }
    } else {
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }
}
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
                            <li class="active">Other Fee</li>
                        </ul>
                    </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <form class="form-inline">
                                    <div class="form-group mx-sm-3 mb-2 col-md-offset-2">
                                        <label>Course: </label>
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
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label>Fee Type: </label>
                                        <select name="fee_type" id="fee_type" onchange="getData()">
                                            <option value="">--- Fee Type ---</option>
                                            <option value="regular_fee">Regular Fee</option>
                                            <option value="other_fee">Other Fee</option>
                                        </select>
                                    </div>
                                </form>
                                <br>
                                <br>

                                <form action="" method="post" class="form-horizontal hideMe" role="form" id="adm_form">
                                    <input type="hidden" name="course_id" id="course_id"/>
                                    <input type="hidden" name="fee_type_value" id="fee_type_value"/>
                                    <div id="student_form">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3 no-padding-right" for="student_name">STUDENT NAME</label>
                                            <div class="col-xs-12 col-sm-7">
                                                <div class="clearfix">
                                                    <select required="true" id="student_name" name="student_name" class="col-xs-12 col-sm-9 select2" data-placeholder="Please select Student name...">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button class="btn btn-info" type="button" id="submitBtn">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Submit
                                            </button>
                                            &nbsp; &nbsp; &nbsp;
                                            <button class="btn" type="button">
                                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--                        <div class="row">
                                                    <h5><b>Addition of Fees added: </b><span id="form_total" style="background: lightgray;"></span></h5>
                                                </div>-->
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
        <script src="assets/js/select2.min.js"></script>


        <!-- ace scripts -->
        <script src="assets/js/ace-elements.min.js"></script>
        <script src="assets/js/ace.min.js"></script>

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
                                            function getData() {
                                                if (($('#fee_type').val() == 'regular_fee') && ($('#course').val()) != '') {
                                                    $('.dynamic-content').remove();
                                                    $.ajax({url: "getcourse-fees.php?id=" + ($('#course').val()), success: function (result) {
                                                            var res = JSON.parse(result);
                                                            var available_headers = JSON.parse(res[0]['course_headers']);
                                                            var students_list = res['student_list'];
                                                            $('#student_name').find('option').remove();
                                                            $('<option>').val("").text("").appendTo('#student_name');
                                                            students_list.map(function (student) {
                                                                $('<option>').val(student.student_name).text(student.student_name).appendTo('#student_name');
                                                            });
                                                            var st_content = "<div class='dynamic-content'></div>";
                                                            $('#student_form').append(st_content);
                                                            for (var i = 0; i < available_headers.length; i++) {
                                                                var mid_content = "<div class='form-group'>\n\
                                                                                                    <label class='col-sm-3 control-label no-padding-right'>" + available_headers[i].header + " </label> \n\
                                                                                                    <div class='col-sm-7'> \n\
                                                                                                        <input  name='" + available_headers[i].header.replace(" ", "_") + "' required type='number' id='" + available_headers[i].header.replace(" ", "_") + "' class='col-xs-10 col-sm-9'/> \n\
                                                                                                    </div> \n\
                                                                                                   </div>";
                                                                $('.dynamic-content').append(mid_content);
                                                            }
                                                        }
                                                    });

                                                    $('#adm_form').removeClass('hideMe');
                                                    $('#adm_form').addClass('showMe');
                                                } else if (($('#fee_type').val() == 'other_fee') && ($('#course').val()) != '') {
                                                    $('.dynamic-content').remove();
                                                    var st_content = "<div class='dynamic-content'></div>";
                                                    $('#student_form').append(st_content);
                                                    var content = "\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>DEVELOPMENT FUND</label><div class='col-sm-7'><input  name='DEVELOPMENT_FUND' required type='number' id='DEVELOPMENT_FUND' class='col-xs-10 col-sm-9'/></div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>TUITION FEE</label> <div class='col-sm-7'> <input  name='TUITION_FEE' required type='number' id='TUITION_FEE' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>T C FEE</label> <div class='col-sm-7'> <input  name='T_C_FEE' required type='number' id='T_C_FEE' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>SUBJECT CHANGE FEE</label> <div class='col-sm-7'> <input  name='SUBJECT_CHANGE_FEE' required type='number' id='SUBJECT_CHANGE_FEE' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>COLLEGE CHANGE FEE</label> <div class='col-sm-7'> <input  name='COLLEGE_CHANGE_FEE' required type='number' id='COLLEGE_CHANGE_FEE' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>RE-ADMISSION FEE</label> <div class='col-sm-7'> <input  name='READMISSION_FEE' required type='number' id='READMISSION_FEE' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>FACULTY CHANGE FEE</label> <div class='col-sm-7'> <input  name='FACULTY_CHANGE_FEE' required type='number' id='FACULTY_CHANGE_FEE' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>FINE</label> <div class='col-sm-7'> <input  name='FINE' required type='number' id='FINE' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>RECOVERY COST OF LOST BOOKS</label> <div class='col-sm-7'> <input  name='RECOVERY_COST_OF_LOST_BOOKS' required type='number' id='RECOVERY_COST_OF_LOST_BOOKS' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>KBC NMU EXAM FEE</label> <div class='col-sm-7'> <input  name='KBC_NMU_EXAM_FEE' required type='number' id='KBC_NMU_EXAM_FEE' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>HSC EXAM FEE</label> <div class='col-sm-7'> <input  name='HSC_EXAM_FEE' required type='number' id='HSC_EXAM_FEE' class='col-xs-10 col-sm-9'/> </div></div>\n\
                                                        <div class='form-group'><label class='col-sm-3 control-label no-padding-right'>LIBRARY FINE</label> <div class='col-sm-7'> <input  name='LIBRARY_FINE' required type='number' id='LIBRARY_FINE' class='col-xs-10 col-sm-9'/> </div></div></div>";
                                                    $('.dynamic-content').append(content);
                                                    $('#adm_form').removeClass('hideMe');
                                                    $('#adm_form').addClass('showMe');
                                                } else {
                                                    $('.dynamic-content').remove();
                                                    $('#adm_form').addClass('hideMe');
                                                }
                                            }
                                            jQuery(function ($) {
                                                $('.select2').css('width', '75%').select2({allowClear: true})
                                                        .on('change', function () {
//                                                            $(this).closest('form').validate().element($(this));
                                                        });
                                                $("#student_name").select2({
                                                    tags: true
                                                });
                                                $('#adm_form').on('keyup keypress', function (e) {
                                                    var keyCode = e.keyCode || e.which;
                                                    if (keyCode === 13) {
                                                        e.preventDefault();
                                                        return false;
                                                    }
                                                });
                                                $("#submitBtn").click(function (e) {
                                                    if ((parseInt($('#course').val())) && (1)) {
                                                        e.preventDefault();
                                                        console.log(($('#student_name').val()))
                                                        if (($('#student_name').val())) {
                                                            if (window.confirm("Are you sure, you want to submit the form?")) {
                                                                $('#course_id').val($('#course').val());
                                                                $('#fee_type_value').val($('#fee_type').val());
                                                                $('#regular_fee').attr('name', $('#header_list').val());
                                                                $("#adm_form").submit();
                                                            }
                                                        } else {
                                                            alert('Please select student name');
                                                            return;
                                                        }
                                                    } else {
                                                        alert('Please select course');
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

                                            }
                                            )
        </script>
    </body>
</html>
<?php
//}
?>