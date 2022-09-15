<?php
session_start();
$user_data = [];
include 'data_manipulator_config.php';
$link = config();
if (!empty($_POST)) {
    $roll_number = $_POST['student_roll_number'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $prn_number = $_POST['prn_number'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $religion = $_POST['religion'];
    $category = $_POST['category'];
    $handicap = $_POST['handicap'];
    $address = $_POST['address'];
    $aadhar_number = $_POST['aadhar_number'];
    $created_by = $_POST['created_by'];
    $date = date('Y-m-d H:i:s');

    $sql = "UPDATE students SET "
            . "first_name = '$first_name',"
            . "middle_name = '$middle_name',"
            . "last_name = '$last_name',"
            . "prn_number = '$prn_number',"
            . "phone_nmuber = '$phone_number',"
            . "email = '$email',"
            . "birth_date = '$dob',"
            . "gender = '$gender',"
            . "religion = '$religion',"
            . "category = '$category',"
            . "handicap = '$handicap',"
            . "address = '$address',"
            . "aadhar_number = '$aadhar_number',"
            . "created_by = '$created_by',"
            . "active_roll_number = '$roll_number',"
            . "modified_date = '$date' "
            . "WHERE students.id = '" . $_POST['id'] . "'";

    if (mysqli_query($link, $sql)) {
        $url = $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_NAME'] == 'localhost')
            header('Location: http://localhost/acsc/admissions.php');
        else
            header('Location: http://' . $url . '/admissions.php');
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        die();
    }
}
$sql = "SELECT * "
        . "FROM students WHERE id = '" . $_GET['id'] . "'";
$result = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($result, $user_data);
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

$sql = "SELECT * from course";
$list_of_courses = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($list_of_courses, $user_data);
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
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
                                <a href="#">Students</a>
                            </li>
                            <li class="active">Student Details</li>
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
                                    <div class="col-xs-12">
                                        <div class="table-header">
                                            Update Student Details
                                        </div>
                                        <div>
                                            <br>
                                            <br>

                                            <form action="" method="post" class="form-horizontal" role="form" id="adm_form">
                                                <?php if ($_SESSION['user'] == 'Amol Patil') { ?>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Class </label>
                                                        <div class="col-sm-9">
                                                            <label class="block clearfix">
                                                                <span class="block input-icon input-icon-right">
                                                                    <select name="course" id="course" class="col-xs-10 col-sm-7">
                                                                        <option value="">---Course Name---</option>
                                                                        <?php for ($course = 0; $course < count($list_of_courses); $course++) { ?>
                                                                            <?php if (($result[0]['class'] == $list_of_courses[$course]['id'])) { ?>
                                                                                <option  selected="true" value="<?php echo $list_of_courses[$course]['id']; ?>"><?php echo $list_of_courses[$course]['course_name']; ?></option>
                                                                            <?php } else { ?>
                                                                                <option value="<?php echo $list_of_courses[$course]['id']; ?>"><?php echo $list_of_courses[$course]['course_name']; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> STUDENT FIRST NAME </label>
                                                    <div class="col-sm-7">
                                                        <input  name="first_name" value="<?php echo $result[0]['first_name']; ?>" type="text" id="form-field-icon-1" placeholder="First name" class="col-xs-10 col-sm-9"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> MIDDLE NAME </label>
                                                    <div class="col-sm-7">
                                                        <input  name="middle_name" value="<?php echo $result[0]['middle_name']; ?>" type="text" id="form-field-icon-2" placeholder="Middle name" class="col-xs-10 col-sm-9"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> LAST NAME </label>
                                                    <div class="col-sm-7">
                                                        <input  name="last_name" value="<?php echo $result[0]['last_name']; ?>" type="text" id="form-field-icon-3" placeholder="Last name" class="col-xs-10 col-sm-9"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1">  Student Roll Number</label>
                                                    <div class="col-sm-7">
                                                        <input  name="id" value="<?php echo $_GET['id']; ?>" type="hidden"/>
                                                        <input  name="student_roll_number" value="<?php echo $result[0]['active_roll_number']; ?>" type="text" id="roll_number" placeholder="Last name" class="col-xs-10 col-sm-9"/>
                                                    </div>
                                                </div>
                                                <input  type="hidden" id="created_by" name="updated_by" value="<?php echo $_SESSION['user']; ?>" />
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> MOBILE NUMBER </label>
                                                    <div class="col-sm-9">
                                                        <input  name="phone_number" value="<?php echo $result[0]['phone_nmuber']; ?>" type="number" id="form-field-1" placeholder="MOBILE NUMBER" class="col-xs-10 col-sm-7" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> E-mail Id </label>
                                                    <div class="col-sm-9">
                                                        <input  type="email" value="<?php echo $result[0]['email']; ?>"id="form-field-1" placeholder="e-mail" name="email" class="col-xs-10 col-sm-7" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> DATE OF BIRTH </label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <input value="<?php echo $result[0]['birth_date']; ?>" type="date" class="col date-picker" id="id-date-picker-1" name="dob" data-date-format="dd-mm-yyyy" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="gender"> Gender </label>
                                                    <div class="col-sm-9">
                                                        <input name="gender" value="male" type="radio" <?php if ($result[0]['gender'] == 'male') { ?>checked="true" <?php } ?> class="ace input-lg" /> <span class="lbl bigger-120"> Male</span>
                                                        <input name="gender" value="female" type="radio" <?php if ($result[0]['gender'] == 'female') { ?>checked="true" <?php } ?> class="ace input-lg" /> <span class="lbl bigger-120"> Female</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="religion"> RELIGION </label>
                                                    <div class="col-sm-9">
                                                        <select name="religion" id="religion"  class="col-xs-10 col-sm-7">
                                                            <option value="">---------------------------------  RELIGION  --------------------------------</option>
                                                            <option value="Hindu" <?php if (($result[0]['religion'] == 'Hindu')) { ?> selected="true" <?php } ?>>Hindu</option>
                                                            <option value="Muslim" <?php if (($result[0]['religion'] == 'Muslim')) { ?> selected="true" <?php } ?>>Muslim</option>
                                                            <option value="Jain" <?php if (($result[0]['religion'] == 'Jain')) { ?> selected="true" <?php } ?>>Jain</option>
                                                            <option value="Bhudhist" <?php if (($result[0]['religion'] == 'Bhudhist')) { ?> selected="true" <?php } ?>>Bhudhist</option>
                                                            <option value="Minority" <?php if (($result[0]['religion'] == 'Minority')) { ?> selected="true" <?php } ?>>Minority</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="category"> CATEGORY </label>
                                                    <div class="col-sm-9">
                                                        <select name="category" id="category"  class="col-xs-10 col-sm-7">
                                                            <option value="">---------------------------------  CATEGORY  --------------------------------</option>
                                                            <option value="SC" <?php if (($result[0]['category'] == 'SC')) { ?> selected="true" <?php } ?>>SC</option>
                                                            <option value="ST" <?php if (($result[0]['category'] == 'ST')) { ?> selected="true" <?php } ?>>ST</option>
                                                            <option value="VJ NT (A)" <?php if (($result[0]['category'] == 'VJ NT (A)')) { ?> selected="true" <?php } ?>>VJ NT (A)</option>
                                                            <option value="NT B" <?php if (($result[0]['category'] == 'NT B')) { ?> selected="true" <?php } ?>>NT B</option>
                                                            <option value="NT C" <?php if (($result[0]['category'] == 'NT C')) { ?> selected="true" <?php } ?>>NT C</option>
                                                            <option value="NT D" <?php if (($result[0]['category'] == 'NT D')) { ?> selected="true" <?php } ?>>NT D</option>
                                                            <option value="SBC" <?php if (($result[0]['category'] == 'SBC')) { ?> selected="true" <?php } ?>>SBC</option>
                                                            <option value="OBC" <?php if (($result[0]['category'] == 'OBC')) { ?> selected="true" <?php } ?>>OBC</option>
                                                            <option value="E.S.B.C." <?php if (($result[0]['category'] == 'E.S.B.C.')) { ?> selected="true" <?php } ?>>E.S.B.C.</option>
                                                            <option value="GENERAL" <?php if (($result[0]['category'] == 'GENERAL')) { ?> selected="true" <?php } ?>>GENERAL</option>
                                                            <option value="PTWF" <?php if (($result[0]['category'] == 'PTWF')) { ?> selected="true" <?php } ?>>PTWF</option>
                                                            <option value="HSTWF" <?php if (($result[0]['category'] == 'HSTWF')) { ?> selected="true" <?php } ?>>HSTWF</option>
                                                            <option value="Freedom fighter" <?php if (($result[0]['category'] == 'Freedom fighter')) { ?> selected="true" <?php } ?>>Freedom fighter</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="handicap"> Physically Handicap </label>
                                                    <div class="col-sm-9">
                                                        <input name="handicap" value="Yes" <?php if ($result[0]['handicap'] == 'Yes') { ?>checked="true" <?php } ?> type="radio" class="ace input-lg" />
                                                        <span class="lbl bigger-120"> Yes</span>
                                                        <input name="handicap" value="No" <?php if ($result[0]['handicap'] == 'No') { ?>checked="true" <?php } ?>type="radio" class="ace input-lg" />
                                                        <span class="lbl bigger-120"> No</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ADDRESS </label>
                                                    <div class="col-sm-9">
                                                        <textarea name="address" value="<?php echo $result[0]['address']; ?>" id="form-field-1"  class="col-xs-10 col-sm-7"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> AADHAR CARD NUMBER </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" value="<?php echo $result[0]['aadhar_number']; ?>" id="form-field-1" placeholder="AADHAR number" name="aadhar_number" class="col-xs-10 col-sm-7" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label no-padding-right" for="PRN-NUMBER"> PRN NUMBER </label>
                                                    <div class="col-sm-7">
                                                        <input  name="prn_number" value="<?php echo $result[0]['prn_number']; ?>" type="text" id="PRN-NUMBER" placeholder="PRN NUMBER" class="col-xs-10 col-sm-9" />
                                                    </div>
                                                </div>
                                                <div class="space-4"></div>
                                                <div class="clearfix form-actions">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button class="btn btn-info" type="button" id="submitBtn">
                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                            Update
                                                        </button>
                                                        &nbsp; &nbsp; &nbsp;
                                                        <button class="btn" type="reset">
                                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
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
                function confirmDelete(admission_form_id) {
                    var r = confirm("Are you Sure, You want to update this record!");
                    if (r == true) {
                        window.location.replace("update-admission-form.php?form=" + admission_form_id);
                    }
                }
                jQuery(function ($) {
                    var o_course = $("#course").val();
                    var o_roll_number = $("#roll_number").val();
                    $('#adm_form').on('keyup keypress', function (e) {
                        var keyCode = e.keyCode || e.which;
                        if (keyCode === 13) {
                            e.preventDefault();
                            return false;
                        }
                    });
                    $("#submitBtn").click(function (e) {
                        //                            if (parseInt($('#course').val())) {
                        if ((o_roll_number == ($("#roll_number").val())) && (o_course != ($("#course").val()))) {
                            if (window.confirm("You are changing the class, there is chance of Roll Number overlapping. Are you sure to update the record, without Roll Number update?")) {
                                e.preventDefault();
                                if (window.confirm("Are you sure, you want to submit the form?")) {
                                    $("#adm_form").submit();
                                }
                            }
                        } else {
                            e.preventDefault();
                            if (window.confirm("Are you sure, you want to submit the form?")) {
                                $("#adm_form").submit();
                            }
                        }
                        //                            } else {
                        //                                alert('Please select course first');
                        //                            }
                    });
                    //initiate dataTables plugin
                    var myTable =
                            $('#dynamic-table')
                            .DataTable({
                                bAutoWidth: false,
                                "aaSorting": [],
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
                                autoPrint: false,
                                message: 'This print was produced using the Print button for DataTables'
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
                    $('.show-details-btn').on('click', function (e) {
                        e.preventDefault();
                        $(this).closest('tr').next().toggleClass('open');
                        $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
                    });
                })
        </script>
    </body>
</html>
