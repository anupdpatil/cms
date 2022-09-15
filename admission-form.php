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
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="widget-box">
                                    <div class="widget-header widget-header-blue widget-header-flat">
                                        <h4 class="widget-title lighter">Admission Form </h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div id="fuelux-wizard-container">
                                                <ul class="steps">
                                                    <li data-step="1" class="active">
                                                        <span class="step">1</span>
                                                        <span class="title">Student Information</span>
                                                    </li>
                                                    <li data-step="3">
                                                        <span class="step">3</span>
                                                        <span class="title">Payment Information</span>
                                                    </li>
                                                    <li data-step="4">
                                                        <span class="step">4</span>
                                                        <span class="title">Receipt Preview</span>
                                                    </li>
                                                </ul>
                                                <hr />
                                                <div class="step-content pos-rel">
                                                    <div class="step-pane active" data-step="1">
                                                        <form class="form-horizontal" id="validation-form">
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="course">Course:</label>
                                                                <div class="col-xs-12 col-sm-10">
                                                                    <div class="clearfix">
                                                                        <select name="course" id="course" class="col-xs-12 col-sm-9" placeholder="Select Course">
                                                                            <option value="">Select Course</option>
                                                                            <?php for ($index = 0; $index < count($result); $index++) { ?>
                                                                                <option value="<?php echo $result[$index]['id']; ?>&group=<?php echo $result[$index]['course_group']; ?>"><?php echo $index + 1 . ". " . $result[$index]['course_name']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="first-name">Student Name:</label>
                                                                <div class="col-xs-12 col-sm-10">
                                                                    <div class="clearfix">
                                                                        <input name="first_name" type="text" id="first_name" class="col-xs-12 col-sm-3"  placeholder="First name"/>
                                                                        <input  name="middle_name" type="text" id="middle_name" placeholder="Middle name" class="col-xs-12 col-sm-3"/>
                                                                        <input  name="last_name" type="text" id="last_name" placeholder="Last name" class="col-xs-12 col-sm-3"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="phone">Phone Number:</label>
                                                                <div class="col-xs-12 col-sm-10">
                                                                    <div class="input-group" style="padding-right:13px">
                                                                        <span class="input-group-addon">
                                                                            <i class="ace-icon fa fa-phone"></i>
                                                                        </span>
                                                                        <input type="tel" id="phone" name="phone" class="col-xs-12 col-sm-9"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email">Email Address:</label>
                                                                <div class="col-xs-12 col-sm-10">
                                                                    <div class="clearfix">
                                                                        <input type="email" name="email" id="email" class="col-xs-12 col-sm-9" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-2 control-label no-padding-right" for="dob"> DATE OF BIRTH:</label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group" style="padding-right:13px">
                                                                        <span class="input-group-addon">
                                                                            <i class="ace-icon fa fa-calendar"></i>
                                                                        </span>
                                                                        <input  class="col-xs-12 col-sm-9 date-picker" id="dob" name="dob" type="text" data-date-format="dd-mm-yyyy" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-2 control-label no-padding-right" for="aadhar_number">AADHAR Number:</label>
                                                                <div class="col-sm-10">
                                                                    <input  type="text" id="aadhar_number" placeholder="AADHAR number" name="aadhar_number" class="col-xs-12 col-sm-9" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="gender">Gender:</label>
                                                                <div class="col-xs-12 col-sm-10">
                                                                    <div class="clearfix">
                                                                        <select name="gender" id="gender" class="col-xs-12 col-sm-9" placeholder="Gender">
                                                                            <option value="male">Male</option>
                                                                            <option value="female">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="handicap">Physical Handicap:</label>
                                                                <div class="col-xs-12 col-sm-10">
                                                                    <div class="clearfix">
                                                                        <select name="handicap" id="handicap" class="col-xs-12 col-sm-9" placeholder="handicap">
                                                                            <option value="No">No</option>
                                                                            <option value="Yes">Yes</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="fee-type">Fee Type:</label>
                                                                <div class="col-xs-12 col-sm-10">
                                                                    <div class="clearfix">
                                                                        <select name="fee-type" id="fee-type" class="col-xs-12 col-sm-9" placeholder="Fee Type">
                                                                            <option value="paying">Paying</option>
                                                                            <option value="non paying">GOI</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="religion">Religion:</label>
                                                                <div class="col-xs-12 col-sm-10">
                                                                    <div class="clearfix">
                                                                        <select name="religion" id="religion" class="col-xs-12 col-sm-9" placeholder="religion">
                                                                            <option value="Hindu">Hindu</option>
                                                                            <option value="Muslim">Muslim </option>
                                                                            <option value="Jain">Jain</option>
                                                                            <option value="Bhudhist">Bhudhist</option>
                                                                            <option value="Minority">Minority</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="category">Category:</label>
                                                                <div class="col-xs-12 col-sm-10">
                                                                    <div class="clearfix">
                                                                        <select name="category" id="category" class="col-xs-12 col-sm-9" placeholder="Category">
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
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <div class="step-pane" data-step="2">
                                                        <div>
                                                            <div class="alert alert-success">
                                                                <button type="button" class="close" data-dismiss="alert">
                                                                    <i class="ace-icon fa fa-times"></i>
                                                                </button>

                                                                <strong>
                                                                    <i class="ace-icon fa fa-check"></i>
                                                                    Well done!
                                                                </strong>

                                                                You successfully read this important alert message.
                                                                <br />
                                                            </div>

                                                            <div class="alert alert-danger">
                                                                <button type="button" class="close" data-dismiss="alert">
                                                                    <i class="ace-icon fa fa-times"></i>
                                                                </button>

                                                                <strong>
                                                                    <i class="ace-icon fa fa-times"></i>
                                                                    Oh snap!
                                                                </strong>

                                                                Change a few things up and try submitting again.
                                                                <br />
                                                            </div>

                                                            <div class="alert alert-warning">
                                                                <button type="button" class="close" data-dismiss="alert">
                                                                    <i class="ace-icon fa fa-times"></i>
                                                                </button>
                                                                <strong>Warning!</strong>

                                                                Best check you self, you're not looking too good.
                                                                <br />
                                                            </div>

                                                            <div class="alert alert-info">
                                                                <button type="button" class="close" data-dismiss="alert">
                                                                    <i class="ace-icon fa fa-times"></i>
                                                                </button>
                                                                <strong>Heads up!</strong>

                                                                This alert needs your attention, but it's not super important.
                                                                <br />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="step-pane" data-step="3">
                                                        <div class="center">
                                                            <form id="invoice_form" class="form-horizontal">

                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="step-pane" data-step="4">
                                                        <div class="row">
                                                            <div class="col-xs-2">
                                                            </div>
                                                            <div class="col-xs-8">
                                                                <div class="card card-body printableArea">
                                                                    <div class="row">
                                                                        <div class="col-xs-12">
                                                                            <div class="col-xs-12" style="border: black solid 1px; padding: 1px; border-radius: 5px;">
                                                                                <div class="col-md-12">
                                                                                    <div class="col-xs-2">
                                                                                        <img class="nav-user-photo" src="assets/images/gallery/acsc_logo.jpeg" width="50" height="50" alt="College Logo" />
                                                                                    </div>
                                                                                    <div class="col-xs-10">
                                                                                        Jamner Taluka Education Society's<br>
                                                                                        <b>GITABAI DATTATRAY MAHAJAN ARTS, SHRI KESHARIMAL RAJMAL NAVLAKHA COMMERCE AND MANOHARSHETH DHARIWAL SCIENCE COLLEGE, JAMNER</b>
                                                                                        Tal. Jamner Dist. Jalgaon
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12">
                                                                            <div class="col-xs-12" style="text-align: center; padding: 1PX;">
                                                                                <u>(ADMISSION FEE RECEIPT)</u>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12">
                                                                            <div class="col-xs-12" style="border: black solid 1px; padding: 1px; border-radius: 5px;">
                                                                                <div class="col-md-12">
                                                                                    <div class="col-md-8">
                                                                                        Rec. No. :*****
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        Date:<?php echo date('d/m/Y'); ?>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="col-md-8">
                                                                                        Course : <span id="course-text"></span>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        Student ID: *****
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="col-md-8">
                                                                                        Category: <span id="category-text"></span>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        Fee Type: <span id="fee-type-text"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="col-md-8">
                                                                                        Name: <span id="student-first-name"></span> <span id="student-middle-name"></span> <span id="student-last-name"></span>
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        Roll No:*****
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-12">
                                                                            <div class="col-xs-12" style="font-size: small;" id="receipt-table">

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- PAGE CONTENT ENDS -->
                                                            </div><!-- /.col -->
                                                        </div><!-- /.row -->
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="wizard-actions">
                                                <button class="btn btn-prev">
                                                    <i class="ace-icon fa fa-arrow-left"></i>
                                                    Prev
                                                </button>
                                                <button class="btn btn-success btn-next" data-last="Submit">
                                                    Next
                                                    <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                </button>
                                            </div>
                                        </div><!-- /.widget-main -->
                                    </div><!-- /.widget-body -->
                                </div>
                            </div><!-- /.col -->
                        </div>
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->
            <?php include 'footer.php'; ?>
        </div><!-- /.main-container -->
        <script src="assets/js/jquery-2.1.4.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

        <!-- page specific plugin scripts -->
        <script src="assets/js/wizard.min.js"></script>
        <script src="assets/js/jquery.validate.min.js"></script>
        <script src="assets/js/jquery-additional-methods.min.js"></script>
        <script src="assets/js/bootbox.js"></script>
        <script src="assets/js/jquery.maskedinput.min.js"></script>
        <script src="assets/js/select2.min.js"></script>
        <script src="assets/js/bootstrap-datepicker.min.js"></script>
        <!-- ace scripts -->
        <script src="assets/js/ace-elements.min.js"></script>
        <script src="assets/js/ace.min.js"></script>
        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement)
                document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
            jQuery(function ($) {
                var total = 0;
                var receipt_data = [];
                var student_data = [];
                $('#invoice_form').on('change', 'input', function (e) {
                    total = 0;
                    $("form#invoice_form :input").each(function () {
                        total += parseInt($(this).val());
                    });
                            receipt_data[receipt_data.findIndex(x => Object.keys(x)[0] === e.target.name)][e.target.name] = [(e.target.value == "") ? 0 : e.target.value];
                            $("#form_total").text(total);
                });
                $('[data-rel=tooltip]').tooltip();
                $('.select2').css('width', '200px').select2({allowClear: true}).on('change', function () {
                    $(this).closest('form').validate().element($(this));
                });
                $('#first_name').on('change', function () {
                    $("#student-first-name").text($('#first_name').val());
                })
                $('#middle_name').on('change', function () {
                    $("#student-middle-name").text($('#middle_name').val());
                })
                $('#last_name').on('change', function () {
                    $("#student-last-name").text($('#last_name').val());
                })
                $('#category').on('change', function () {
                    $("#category-text").text($('#category').val());
                })
                $('#fee-type').on('change', function () {
                    $("#fee-type-text").text($('#fee-type').val());
                })
                $('#course').on('change', function () {
                    $("#course-text").text($('#course').find(":selected").text());
                })
                $('.date-picker').datepicker({
                    endDate: '-14y',
                    autoclose: true,
                    todayHighlight: true
                })
                function setStudentDetails() {
                    student_data = [];
                            student_data.push({[$("#course").attr("name")]:[$('#course').find(":selected").val()]});
                            student_data.push({[$("#first_name").attr("name")]:[$('#first_name').val()]});
                            student_data.push({[$("#middle_name").attr("name")]:[$('#middle_name').val()]});
                            student_data.push({[$("#last_name").attr("name")]:[$('#last_name').val()]});
                            student_data.push({[$("#phone").attr("name")]:[$('#phone').val()]});
                            student_data.push({[$("#email").attr("name")]:[$('#email').val()]});
                            student_data.push({[$("#dob").attr("name")]:[$('#dob').val()]});
                            student_data.push({[$("#aadhar_number").attr("name")]:[$('#aadhar_number').val()]});
                            student_data.push({[$("#gender").attr("name")]:[$('#gender').find(":selected").val()]});
                            student_data.push({[$("#handicap").attr("name")]:[$('#handicap').find(":selected").val()]});
                            student_data.push({[$("#fee-type").attr("name")]:[$('#fee-type').find(":selected").val()]});
                            student_data.push({[$("#religion").attr("name")]:[$('#religion').find(":selected").val()]});
                            student_data.push({[$("#category").attr("name")]:[$('#category').find(":selected").val()]});
                }
                function getReceiptValues() {
                    $('#receipt').remove();
                    $("#receipt-table").append('<table class="table table-condensed" id="receipt"></table>');
                    total = 0;
                    var rows = receipt_data.map(function (d) {

                        if ((d[Object.keys(d)[0]][0] == 0)) {
                        } else {
                            total += parseInt(d[Object.keys(d)[0]][0]);
                            return "<tr><td>" + Object.keys(d)[0] + "</td><td style='text-align: right;'>" + d[Object.keys(d)[0]][0] + "</td></tr>";
                        }
                    });
                    $('#receipt').append(rows);
                    $('#receipt').append("<tr><td><b>Total Amount:</b></td><td style='text-align: right;'><b>Rs. " + total + "/-</b></td></tr><tr><td></td><td><b>Receiver's Signature:</b></td></tr>");
                }
                function nextBtnClicked() {
                    $("#category-text").text($('#category').val());
                    $("#fee-type-text").text($('#fee-type').val());
                    $('#fee-structure-form').remove();
                    setStudentDetails();
                    receipt_data = [];
                    var selected_course_fees = <?php echo json_encode($result); ?>;
                    var course = $('#course').find(":selected").val().split("&")[0];
                    var fee_type = $('#fee-type').find(":selected").val();
                    var heads = [];
                    if (fee_type === 'non paying')
                        heads = ["TUITION FEE", "ADD FEE", "TUTION FEE", "TERM FEE", "ADMISSION FEE", "ADMISSION FFE", "LIBRARY FEE", "MEDICAL FEE", "GYMKHANA FEE", "POOR STUD AID FUND", "STUDENTS ACTIVITY FEE", "LABORATORY FEE", "STUDENTS GROUP INS (UNI)", "STUDENTS GROUP INS (UNI) FEE", "COMPUTERIZATION FEE", "COLLEGE DEVELOPMENT FUND", "COLLEGE DEVELOPMENT FUND FEE", "ASHWAMEDH FEE", "IDENTITY FEE", "YUVA RANG/YOUTH FESTIVEL"];
                    var total = 0;
                    var data = (JSON.parse(selected_course_fees[course - 1]['fees_breakup']));
                    $('#invoice_form').append('<div id="fee-structure-form"></div>');
                    var elements = data.map(function (d, index) {
                        if ($.inArray(d.header, heads) != - 1) {
                        } else {
                        receipt_data.push({[d.header.replace(/ /g, '_')]:[parseInt(d.value)]});
                                total += parseInt(d.value);
                        return "<div class='form-group'><label class='col-sm-3 control-label no-padding-right' for='form-field-1'>" + d.header + "</label><div class='col-sm-7'><input name='" + d.header.replace(/ /g, '_') + "' type='number' id='" + d.header.replace(/ /g, '_') + "' value='" + d.value + "' max='" + d.value + "' class='col-xs-10 col-sm-9 fee-structure-form-element'></div></div>"
                        }
                    });
                    $('#fee-structure-form').append(elements);
                    $('#fee-structure-form').append("<div class='row'><h5><b>Addition of Fees: </b><span id='form_total' style='background: lightgray;'><u>" + total + "</u></span></h5></div>");
                }
                function createAdmission(url, data) {
                    var request = $.ajax({
                        url: url,
                        type: "POST",
                        data: data,
                    });
                    request.done(function (msg) {
                        var obj = JSON.parse(msg);
                        bootbox.alert({
                            closeButton: false,
                            message: obj.message,
                            callback: function () {
                                if (obj.success)
                                    window.location.replace("invoice.php?invoice_id=" + parseInt(obj.data.invoice_id) + "&class=" + parseInt(obj.data.class) + "&form=" + parseInt(obj.data.form_id));
                                else
                                    window.location.reload();
                            }
                        })
                    });
                    request.fail(function (xhr, textStatus, errorThrown) {
                        bootbox.alert({
                            closeButton: false,
                            message: textStatus,
                            callback: function () {
                                window.location.reload();
                            }
                        })
                    });
                }
                var $validation = true;
                $('#fuelux-wizard-container')
                        .ace_wizard({
                            //step: 2 //optional argument. wizard will jump to step "2" at first
                            //buttons: '.wizard-actions:eq(0)'
                        })
                        .on('actionclicked.fu.wizard', function (e, info) {
                            if (info.step == 1 && $validation) {
                                if (!$('#validation-form').valid())
                                    e.preventDefault();
//                                    console.log(e);
                                nextBtnClicked();
                            }
                            if (info.step == 2)
                                getReceiptValues();
                        })
                        //.on('changed.fu.wizard', function() {
                        //})
                        .on('finished.fu.wizard', function (e) {
                            if (window.confirm("Are you sure, you want to submit the form?")) {
                                var invoice = [];
                                receipt_data.map(function (data) {
                                    var val = parseInt(data[Object.keys(data)][0]);
                                    var key = (Object.keys(data)[0]).replace(/ /g, "_");
                                            var obj = {[key]:val};
                                    invoice.push(obj);
                                })
                                receipt_data = invoice.reduce(function (result, current) {
                                    return Object.assign(result, current);
                                }, {});
                                createAdmission("create-admission.php", {student_data: student_data, invoice_data: receipt_data});
                            }
                        }).on('stepclick.fu.wizard', function (e) {
                });
//         documentation : http://docs.jquery.com/Plugins/Validation/validate

                $.mask.definitions['~'] = '[+-]';
                jQuery.validator.addMethod("phone", function (value, element) {
                    return this.optional(element) || (value.length === 10 && !isNaN(value));
                }, "Enter a valid phone number.");
                $('#validation-form').validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
//                            email: {
//                                required: true,
//                                email: true
//                            },
                        course: {
                            required: true
                        },
                        first_name: {
                            required: true
                        },
                        middle_name: {
                            required: true
                        },
                        last_name: {
                            required: true
                        },
                        phone: {
                            required: true,
                            phone: 'required'
                        },
                        dob: {
                            required: true
                        }
                    },
                    messages: {
                        course: {
                            required: "Please select a course.",
                        },
                        phone: {
                            required: "Please specify a phone number.",
                            minlength: "Please specify a valid phone number."
                        },
                        first_name: {
                            required: "Please specify First Name."
                        },
                        middle_name: {
                            required: "Please specify Middle Name."
                        },
                        last_name: {
                            required: "Please specify Last Name."
                        },
                        dob: {
                            required: "Please specify DOB."
                        },
                    },
                    highlight: function (e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },
                    success: function (e) {
                        $(e).closest('.form-group').removeClass('has-error'); //.addClass('has-info');
                        $(e).remove();
                    },
                    errorPlacement: function (error, element) {
                        if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                            var controls = element.closest('div[class*="col-"]');
                            if (controls.find(':checkbox,:radio').length > 1)
                                controls.append(error);
                            else
                                error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        } else if (element.is('.select2')) {
                            error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                        } else if (element.is('.chosen-select')) {
                            error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                        } else
                            error.insertAfter(element.parent());
                    },
                    submitHandler: function (form) {
                    },
                    invalidHandler: function (form) {
                    }
                });
                $('#modal-wizard-container').ace_wizard();
                $('#modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');
                /**
                 $('#date').datepicker({autoclose:true}).on('changeDate', function(ev) {
                 $(this).closest('form').validate().element($(this));
                 });
                 
                 $('#mychosen').chosen().on('change', function(ev) {
                 $(this).closest('form').validate().element($(this));
                 });
                 */


                $(document).one('ajaxloadstart.page', function (e) {
                    //in ajax mode, remove remaining elements before leaving page
                    $('[class*=select2]').remove();
                });
            })
        </script>
    </body>
</html>