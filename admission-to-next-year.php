<?php
session_start();
include 'data_manipulator_config.php';

if (empty($_POST) && isset($_GET['class'])) {
    $link = config();
    $sql = "SELECT * from course where id = " . $_GET['class'];
    $result = [];
    if ($res = mysqli_query($link, $sql)) {
        while ($user_data = mysqli_fetch_assoc($res)) {
            array_push($result, $user_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }
    $new_sql = "SELECT * from admission_form where id=" . $_GET['form'];
    $form_data = [];
    if ($ad_res = mysqli_query($link, $new_sql)) {
        while ($admission_form_data = mysqli_fetch_assoc($ad_res)) {
            array_push($form_data, $admission_form_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }
    mysqli_close($link);
}

if (!empty($_POST)) {
    $link = config();
    $sql = "SELECT COUNT( ad.id ) as cnt
FROM admission_form ad
JOIN course cr ON cr.id = ad.class
WHERE cr.course_group = '" . $_GET['group'] . "' AND ad.created_by = '" . $_SESSION['user'] . "'";
    $cr = [];
    if ($res = mysqli_query($link, $sql)) {
        while ($cr_data = mysqli_fetch_assoc($res)) {
            array_push($cr, $cr_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }
    $re_sql = "SELECT admission_id, total_repayments FROM repayment WHERE admission_id IN (SELECT ad.id 
FROM admission_form ad
JOIN course cr ON cr.id = ad.class
WHERE cr.course_group = '" . $_GET['group'] . "' AND ad.created_by = '" . $_SESSION['user'] . "')";

    $inv = [];
    if ($res = mysqli_query($link, $re_sql)) {
        while ($inv_data = mysqli_fetch_assoc($res)) {
            array_push($inv, $inv_data);
        }
    } else {
        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
    }
    $count = 0;
    for ($a = 0; $a < count($inv); $a++) {
        $count += $inv[$a]['total_repayments'];
    }
    if ($count == 0) {
        //search for the repayments current user has done. Count should be increamented beyond that.
    }
    $temp_cnt = $count + 1;
    $invoice_number = 'C' . $_SESSION['id'] . '-' . $_GET['group'] . '-' . date('Y') . '-' . $temp_cnt;
    $data = json_encode($_POST);
    $created_by = $_SESSION['user'];
    $no_of_downloads = 1;
    $date = date('Y-m-d H:i:s');

    $next_course = $_GET['class'];

    $sql = "INSERT INTO admission_form (class,first_name,middle_name,last_name,prn_number,phone_nmuber,whatsup_number,email,dob,gender,religion,fee_type,category,handicap,address,aadhar_number,last_year_marks,last_year_cgpa,last_college_name,student_bank_account,ifsc_code,created_by,invoice_id,student_roll_number,user_image,form_image,created_date,modified_date) 
 SELECT '" . $next_course . "', first_name, middle_name, last_name, prn_number, phone_nmuber, whatsup_number, email, dob, gender, religion, fee_type, category, handicap, address, aadhar_number, last_year_marks, last_year_cgpa, last_college_name, student_bank_account, ifsc_code, '" . $_SESSION['user'] . "', '" . 0 . "', student_roll_number, user_image, form_image, '$date', '$date' from admission_form 
 WHERE id='" . $_GET['form'] . "'";
    if (mysqli_query($link, $sql)) {
        $form_id = $link->insert_id;
        $sql = "INSERT INTO invoices (invoice_no,data,created_by,no_of_downloads, created_date) VALUES ("
                . "'$invoice_number',"
                . "'$data',"
                . "'$created_by',"
                . "'$no_of_downloads',"
                . "'$date');";
        if (mysqli_query($link, $sql)) {
            $invoice_id = $link->insert_id;
            $new_sql = "UPDATE admission_form SET invoice_id='$invoice_id' WHERE id=" . $form_id;
            if (mysqli_query($link, $new_sql)) {
                $new_sql = "UPDATE students SET active_admission_id='$form_id',active_class='$next_course' WHERE active_admission_id='" . $_GET['form'] . "'";
                if (mysqli_query($link, $new_sql)) {
                    $repayment_date = json_encode((object) (array('invoice_id' => 0, 'invoice_date' => $date, 'created_by' => $_SESSION['user'])));
                    $repayment_sql = "INSERT INTO repayment (admission_id,first_invoice_id,total_repayments,data,modified_on,repayment_dates) VALUES ("
                            . "'$form_id',"
                            . "'$invoice_id',"
                            . "'1',"
                            . "'$data',"
                            . "'$date',"
                            . "'$repayment_date');";
                    if (mysqli_query($link, $repayment_sql)) {
                        $sql = "insert into past_admissions select * from admission_form WHERE id='" . $_GET['form'] . "'";
                        if (mysqli_query($link, $sql)) {
                            $sql = "insert into past_invoices select i.* from invoices i JOIN admission_form ad on ad.invoice_id=i.id WHERE ad.id='" . $_GET['form'] . "'";
                            if (mysqli_query($link, $sql)) {
                                $past_invoice_id = $link->insert_id;
                                $sql = "insert into past_repayment select * from repayment WHERE admission_id='" . $_GET['form'] . "'";
                                if (mysqli_query($link, $sql)) {
                                    $sql = "DELETE FROM admission_form WHERE id='" . $_GET['form'] . "'";
                                    if (mysqli_query($link, $sql)) {
                                        $sql = "DELETE FROM invoices WHERE id='$past_invoice_id'";
                                        if (mysqli_query($link, $sql)) {
                                            $sql = "DELETE FROM repayment WHERE admission_id='" . $_GET['form'] . "'";
                                            if (mysqli_query($link, $sql)) {
                                                $url = $_SERVER['SERVER_NAME'];
                                                if ($_SERVER['SERVER_NAME'] == 'localhost')
                                                    header('Location: http://localhost/acsc/invoice.php?repayment=1&invoice_id=' . $invoice_id . '&class=' . $_GET['class'] . '&form=' . $form_id);
                                                else
                                                    header('Location: https://' . $url . "/invoice.php?repayment=1&invoice_id=" . $invoice_id . '&class=' . $_GET['class'] . '&form=' . $form_id);
                                            } else {
                                                die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
                                            }
                                        } else {
                                            die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
                                        }
                                    } else {
                                        die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
                                    }
                                } else {
                                    die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
                                }
                            } else {
                                die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
                            }
                        } else {
                            die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
                        }
                    } else {
                        die("ERROR: Could not able to execute $repayment_sql. " . mysqli_error($link));
                    }
                } else {
                    echo "ERROR: Could not able to execute $new_sql. " . mysqli_error($link);
                }
            } else {
                echo "ERROR: Could not able to execute $new_sql. " . mysqli_error($link);
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
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
                                <li class="active">Admission</li>
                            </ul>
                        </div>

                        <div class="page-content">
                            <div class="page-header">
                                <h1>
                                    Fees Breakup for - <?php echo $result[0]['course_name']; ?>
                                </h1>
                            </div><!-- /.page-header -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <form id="invoice_form" action="" method="post" class="form-horizontal" role="form">
                                        <?php
                                        $header_arr = json_decode($result[0]['fees_breakup']);
                                        for ($index = 0; $index < count($header_arr); $index++) {
                                            if (($form_data[0]['fee_type'] == 'non paying') && 
                                                    ($header_arr[$index]->header == 'TUITION FEE') || ($header_arr[$index]->header == 'TUTION FEE') || ($header_arr[$index]->header == 'ADD FEE') || ($header_arr[$index]->header == 'TERM FEE') || ($header_arr[$index]->header == 'ADMISSION FEE') || ($header_arr[$index]->header == 'ADMISSION FFE') || ($header_arr[$index]->header == 'LIBRARY FEE') || ($header_arr[$index]->header == 'MEDICAL FEE') || ($header_arr[$index]->header == 'GYMKHANA FEE') || ($header_arr[$index]->header == 'POOR STUD AID FUND') || ($header_arr[$index]->header == 'STUDENTS ACTIVITY FEE') || ($header_arr[$index]->header == 'LABORATORY FEE') || ($header_arr[$index]->header == 'STUDENTS GROUP INS (UNI)') || ($header_arr[$index]->header == 'COMPUTERIZATION FEE') || ($header_arr[$index]->header == 'COLLEGE DEVELOPMENT FUND') || ($header_arr[$index]->header == 'ASHWAMEDH FEE') || ($header_arr[$index]->header == 'IDENTITY FEE') || ($header_arr[$index]->header == 'YUVA RANG/YOUTH FESTIVEL')||($header_arr[$index]->header == 'YUVA RANG/YOUTH FESTIVAL')){
                                            } else {
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> <?php echo $header_arr[$index]->header; ?> </label>
                                                    <div class="col-sm-7">
                                                        <input onchange="testMe()" name="<?php echo str_replace(' ', '_', $header_arr[$index]->header); ?>" type="number" id="<?php echo str_replace(' ', '_', $header_arr[$index]->header); ?>" value="<?php echo $header_arr[$index]->value; ?>" class="col-xs-10 col-sm-9"/>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <div class="space-4"></div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
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
                                    </form>

                                </div>
                            </div>
                            <div class="row">
                                <h5><b>Addition of Fees added: </b><span id="form_total" style="background: lightgray;"></span></h5>
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
                                                            function testMe() {
                                                                var form_total = 0;
                                                                $('#invoice_form input').each(
                                                                        //                                    function (index) {
                                                                                function () {
                                                                                    var input = $(this);
                                                                                    //                                        if (index > 1)
                                                                                    form_total += parseInt(input.val());
                                                                                }
                                                                        );
                                                                        $('#form_total').html(form_total);
                                                                    }
                                                            jQuery(function ($) {
                                                                testMe();
                                                                $('#invoice_form').on('keyup keypress', function (e) {
                                                                    var keyCode = e.keyCode || e.which;
                                                                    if (keyCode === 13) {
                                                                        e.preventDefault();
                                                                        return false;
                                                                    }
                                                                });
                                                                $("#submitBtn").click(function (e) {
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