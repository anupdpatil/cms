<?php
session_start();
    include 'data_manipulator_config.php';
if (empty($_POST)) {
        $link = config();

    $sql = "SELECT * from course";
    $result = [];
    if ($res = mysqli_query($link, $sql)) {
        while ($user_data = mysqli_fetch_assoc($res)) {
            array_push($result, $user_data);
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    mysqli_close($link);
}

if (!empty($_POST)) {
        $link = config();
    $course_info = explode("&", $_POST['course']);
    $class = $course_info[0];
    $course_group = $course_info[1];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $prn_number = $_POST['prn_number'];
    $phone_number = $_POST['phone_number'];
    $whatsup_number = $_POST['whatsup_number'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $religion = $_POST['religion'];
    $fee_type = $_POST['fee-type'];
    $category = $_POST['category'];
    $handicap = $_POST['handicap'];
    $address = $_POST['address'];
    $aadhar_number = $_POST['aadhar_number'];
    $last_year_marks = $_POST['last_year_marks'];
    $last_year_cgpa = $_POST['last_year_cgpa'];
    $last_college_name = $_POST['last_college_name'];
    $student_bank_account = $_POST['student_bank_account'];
    $ifsc_code = $_POST['ifsc_code'];
    $created_by = $_POST['created_by'];
    $user_image_path = 'path/to/user_image';
    $form_image_path = 'path/to/form_image';
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO admission_form (class, first_name, middle_name, last_name, prn_number, phone_nmuber, whatsup_number, email, dob, gender, religion,fee_type, category,handicap, address, aadhar_number, last_year_marks, last_year_cgpa, last_college_name, student_bank_account, ifsc_code, created_by,user_image, form_image, created_date, modified_date) VALUES ("
            . "'$class',"
            . "'$first_name',"
            . "'$middle_name',"
            . "'$last_name',"
            . "'$prn_number',"
            . "'$phone_number',"
            . "'$whatsup_number',"
            . "'$email',"
            . "'$dob',"
            . "'$gender',"
            . "'$religion',"
            . "'$fee_type',"
            . "'$category',"
            . "'$handicap',"
            . "'$address',"
            . "'$aadhar_number',"
            . "'$last_year_marks',"
            . "'$last_year_cgpa',"
            . "'$last_college_name',"
            . "'$student_bank_account',"
            . "'$ifsc_code',"
            . "'$created_by',"
            . "'$user_image_path',"
            . "'$form_image_path',"
            . "'$date',"
            . "'$date');";
    if (mysqli_query($link, $sql)) {
        $form_id = $link->insert_id;
        $sql = "INSERT INTO students (active_class, active_admission_id,first_name, middle_name, last_name, prn_number, phone_nmuber, email, birth_date,gender, religion, category,handicap, address, aadhar_number, created_by,active_roll_number, user_image,created_date, modified_date) VALUES ("
                . "'$class',"
                . "'$form_id',"
                . "'$first_name',"
                . "'$middle_name',"
                . "'$last_name',"
                . "'$prn_number',"
                . "'$phone_number',"
                . "'$email',"
                . "'$dob',"
                . "'$gender',"
                . "'$religion',"
                . "'$category',"
                . "'$handicap',"
                . "'$address',"
                . "'$aadhar_number',"
                . "'$created_by',"
                . "'0',"
                . "'$user_image_path',"
                . "'$date',"
                . "'$date');";
        if (mysqli_query($link, $sql)) {
            $url = $_SERVER['SERVER_NAME'];
            if ($_SERVER['SERVER_NAME'] == 'localhost')
                header('Location: http://localhost/acsc/create-invoice.php?class=' . $class . '&form=' . $form_id . '&' . $course_group);
            else
                header('Location: https://' . $url . '/create-invoice.php?class=' . $class . '&form=' . $form_id . '&' . $course_group);
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
            die();
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        die();
    }
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>Dashboard</title>
        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
        <link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
        <link rel="stylesheet" href="assets/css/ace-skins.min.css" />
        <link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
        <link rel="stylesheet" href="assets/css/bootstrap-datepicker3.min.css" />
        <script src="assets/js/ace-extra.min.js"></script>
    </head>

    <body class="no-skin">
        <?php include 'navbar.html'; ?>

        <div class="main-container ace-save-state" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.loadState('main-container')
                } catch (e) {
                }
            </script>

            <?php include 'test.php'; ?>

            <div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
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
                                Admission Form
                            </h1>
                        </div><!-- /.page-header -->
                        <div class="row">
                            <div class="col-xs-12">
                                <form action="" method="post" class="form-horizontal" role="form" id="adm_form">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Class </label>
                                        <div class="col-sm-9">
                                            <label class="block clearfix">
                                                <span class="block input-icon input-icon-right">
                                                    <select name="course" id="course" class="col-xs-10 col-sm-7">
                                                        <option value="0">-----------------------------------    Select the Class    -----------------------------------</option>
                                                        <?php for ($index = 0; $index < count($result); $index++) {
                                                            ?>
                                                            <option value="<?php echo $result[$index]['id']; ?>&group=<?php echo $result[$index]['course_group']; ?>"><?php echo $index+1 .". ".$result[$index]['course_name']; ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> STUDENT NAME </label>
                                        <div class="col-sm-7">
                                            <input  name="first_name" type="text" id="form-field-icon-1" placeholder="First name" class="col-xs-10 col-sm-9"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>
                                        <div class="col-sm-7">
                                            <input  name="middle_name" type="text" id="form-field-icon-2" placeholder="Middle name" class="col-xs-10 col-sm-9"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  </label>
                                        <div class="col-sm-7">
                                            <input  name="last_name" type="text" id="form-field-icon-3" placeholder="Last name" class="col-xs-10 col-sm-9"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> MOBILE NUMBER </label>
                                        <div class="col-sm-9">
                                            <input  name="phone_number" type="number" id="form-field-1" placeholder="MOBILE NUMBER" class="col-xs-10 col-sm-7" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Whats App Mobile Number </label>
                                        <div class="col-sm-9">
                                            <input  name="whatsup_number" type="number" id="form-field-1" placeholder="Whats App Mobile Number" class="col-xs-10 col-sm-7" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> E-mail Id </label>
                                        <div class="col-sm-9">
                                            <input  type="email" id="form-field-1" placeholder="e-mail" name="email" class="col-xs-10 col-sm-7" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> DATE OF BIRTH </label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input  class="col-xs-10 col-sm-8 date-picker" id="id-date-picker-1" name="dob" type="text" data-date-format="dd-mm-yyyy" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="gender"> Gender </label>
                                        <div class="col-sm-9">
                                            <input name="gender" value="male" type="radio" class="ace input-lg" />
                                            <span class="lbl bigger-120"> Male</span>
                                            <input name="gender" value="female" type="radio" class="ace input-lg" />
                                            <span class="lbl bigger-120"> Female</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="religion"> RELIGION </label>
                                        <div class="col-sm-9">
                                            <select name="religion" id="religion"  class="col-xs-10 col-sm-7">
                                                <option value="">---------------------------------  RELIGION  --------------------------------</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Muslim">Muslim </option>
                                                <option value="Jain">Jain</option>
                                                <option value="Bhudhist">Bhudhist</option>
                                                <option value="Minority">Minority</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="gender"> Fee Type </label>
                                        <div class="col-sm-9">
                                            <input name="fee-type" value="paying" type="radio" class="ace input-lg" />
                                            <span class="lbl bigger-120"> Paying</span>
                                            <input name="fee-type" value="non paying" type="radio" class="ace input-lg" />
                                            <span class="lbl bigger-120"> GOI </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="category"> CATEGORY </label>
                                        <div class="col-sm-9">
                                            <select name="category" id="category"  class="col-xs-10 col-sm-7">
                                                <option value="">---------------------------------  CATEGORY  --------------------------------</option>
                                                <option value="SC">SC</option>
                                                <option value="ST">ST</option>
                                                <option value="VJ NT (A)">VJ NT (A)</option>
                                                <option value="NT B">NT B</option>
                                                <option value="NT C">NT C</option>
                                                <option value="NT D">NT D</option>
                                                <option value="SBC">SBC</option>
                                                <option value="OBC">OBC</option>
                                                <option value="E.S.B.C.">E.S.B.C.</option>
                                                <option value="GENERAL">GENERAL</option>
                                                <option value="PTWF">PTWF</option>
                                                <option value="HSTWF">HSTWF</option>
                                                <option value="Freedom fighter">Freedom fighter</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="handicap"> Physically Handicap </label>
                                        <div class="col-sm-9">
                                            <input name="handicap" value="Yes" type="radio" class="ace input-lg" />
                                            <span class="lbl bigger-120"> Yes</span>
                                            <input name="handicap" value="No" type="radio" class="ace input-lg" />
                                            <span class="lbl bigger-120"> No</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ADDRESS </label>
                                        <div class="col-sm-9">
                                            <textarea name="address" id="form-field-1"  class="col-xs-10 col-sm-7"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> AADHAR CARD NUMBER </label>
                                        <div class="col-sm-9">
                                            <input  type="text" id="form-field-1" placeholder="AADHAR number" name="aadhar_number" class="col-xs-10 col-sm-7" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="PRN-NUMBER"> PRN NUMBER </label>
                                        <div class="col-sm-7">
                                            <input  name="prn_number" type="text" id="PRN-NUMBER" placeholder="PRN NUMBER" class="col-xs-10 col-sm-9" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="LAST-YEAR-PERCENTAGE"> LAST YEAR PERCENTAGE </label>
                                        <div class="col-sm-9">
                                            <input  type="text" id="LAST-YEAR-PERCENTAGE" placeholder="LAST YEAR PERCENTAGE" name="last_year_marks" class="col-xs-10 col-sm-7" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="TOTAL-MARKS"> TOTAL MARKS </label>
                                        <div class="col-sm-9">
                                            <input  type="text" id="TOTAL-MARKS" placeholder="TOTAL MARKS" name="last_year_marks" class="col-xs-10 col-sm-7" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="CGPA"> CGPA </label>
                                        <div class="col-sm-9">
                                            <input  type="text" id="CGPA" placeholder="CGPA" name="last_year_cgpa" class="col-xs-10 col-sm-7" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="result"> LAST YEAR RESULT </label>
                                        <div class="col-sm-9">
                                            <select name="class" id="result"  class="col-xs-10 col-sm-7">
                                                <option value="0">---------------------------------  Select Last Year's Result  --------------------------------</option>
                                                <option value="1">Pass</option>
                                                <option value="2">Fail</option>
                                                <option value="3">A.T.K.T.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> STUDENT BANK ACCOUNT NUMBER </label>
                                        <div class="col-sm-9">
                                            <input  type="text" id="form-field-1" placeholder="BANK ACCOUNT NUMBER" name="student_bank_account" class="col-xs-10 col-sm-7" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> IFSC CODE </label>
                                        <div class="col-sm-9">
                                            <input  type="text" id="form-field-1" placeholder="IFSC CODE" name="ifsc_code" class="col-xs-10 col-sm-7" />
                                            <input  type="hidden" id="created_by" name="created_by" value="<?php echo $_SESSION['user']; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name of The College Last Year Attended </label>
                                        <div class="col-sm-9">
                                            <input  type="text" id="form-field-1" placeholder="College Name attended" name="last_college_name"class="col-xs-10 col-sm-7" />
                                        </div>
                                    </div>
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
                function updateMenu() {
                    var test = $('#class').val();
                    switch (test) {
                        case '1':
                            $('#PRN-NUMBER').prop('disabled', true);
                            $('#LAST-YEAR-PERCENTAGE').prop('disabled', true);
                            $('#TOTAL-MARKS').prop('disabled', true);
                            $('#CGPA').prop('disabled', true);
                            $('#result').prop('disabled', true);
                            break;
                        case '2':
                            $('#PRN-NUMBER').prop('disabled', false);
                            $('#LAST-YEAR-PERCENTAGE').prop('disabled', false);
                            $('#TOTAL-MARKS').prop('disabled', false);
                            $('#CGPA').prop('disabled', false);
                            $('#result').prop('disabled', false);
                            break;
                        default:
                            $('#PRN-NUMBER').prop('disabled', false);
                            $('#LAST-YEAR-PERCENTAGE').prop('disabled', false);
                            $('#TOTAL-MARKS').prop('disabled', false);
                            $('#CGPA').prop('disabled', false);
                            $('#result').prop('disabled', false);
                    }

                }
                jQuery(function ($) {
                    $('#adm_form').on('keyup keypress', function (e) {
                        var keyCode = e.keyCode || e.which;
                        if (keyCode === 13) {
                            e.preventDefault();
                            return false;
                        }
                    });
                    $("#submitBtn").click(function (e) {
                        if (parseInt($('#course').val())) {
                            e.preventDefault();
                            if (window.confirm("Are you sure, you want to submit the form?")) {
                                $("#adm_form").submit();
                            }
                        } else {
                            alert('Please select course first');
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