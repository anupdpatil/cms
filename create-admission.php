<?php

session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(array('success' => false, 'message' => 'User session destroyed, Please refresh the page.', 'data' => []));
    die;
}
include 'data_manipulator_config.php';
$link = config();

if (empty($_POST) || empty($_POST['student_data']) || empty($_POST['invoice_data'])) {
    die("please send appropriate request");
}
//============ADMISSION FORM DDETAILS===========================================    
$admission_details = $_POST['student_data'][0];
$course_info = explode("&", $admission_details['course'][0]);

$class = $course_info[0];
$course_group = $course_info[1];
$first_name = $_POST['student_data'][1]['first_name'][0];
$middle_name = $_POST['student_data'][2]['middle_name'][0];
$last_name = $_POST['student_data'][3]['last_name'][0];
$phone_number = $_POST['student_data'][4]['phone'][0];
$whatsup_number = $_POST['student_data'][4]['phone'][0];
$email = $_POST['student_data'][5]['email'][0];
$dob = $_POST['student_data'][6]['dob'][0];
$gender = $_POST['student_data'][8]['gender'][0];
$religion = $_POST['student_data'][11]['religion'][0];
$fee_type = $_POST['student_data'][10]['fee-type'][0];
$category = $_POST['student_data'][12]['category'][0];
$handicap = $_POST['student_data'][9]['handicap'][0];
$aadhar_number = $_POST['student_data'][7]['aadhar_number'][0];
//------------------------------------------------
$prn_number = "";
$address = "";
$last_year_marks = "";
$last_year_cgpa = "";
$last_college_name = "";
$student_bank_account = "";
$ifsc_code = "";
$created_by = $_SESSION['user'];
$user_image_path = 'path/to/user_image';
$form_image_path = 'path/to/form_image';
$date = date('Y-m-d H:i:s');

//begin();
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
//==============================================================================
//=================STUDENT DETAILS==============================================
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
        $student_id = $link->insert_id;
//==============================================================================
        $course_group = explode("=", $course_info[1]);
        $sql = "SELECT COUNT( ad.id ) as cnt FROM admission_form ad JOIN "
                . "course cr ON cr.id = ad.class WHERE "
                . "cr.course_group = '" . $course_group[1] . "' AND "
                . "ad.created_by = '" . $_SESSION['user'] . "'";
        $cr = [];
        if ($res = mysqli_query($link, $sql)) {
            while ($cr_data = mysqli_fetch_assoc($res)) {
                array_push($cr, $cr_data);
            }
        } else {
            die("ERROR: Could not able to execute $sql. " . mysqli_error($link));
        }
        $re_sql = "SELECT admission_id, total_repayments FROM repayment WHERE "
                . "admission_id IN (SELECT ad.id  FROM admission_form ad JOIN "
                . "course cr ON cr.id = ad.class WHERE "
                . "cr.course_group = '" . $course_group[1] . "' AND "
                . "ad.created_by = '" . $_SESSION['user'] . "')";

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
        $invoice_number = 'C' . $_SESSION['id'] . '-' . $course_group[1] . '-' . date('Y') . '-' . $temp_cnt;

//$invoice_details = $_POST['invoice_data'];
//$data = json_encode($invoice_details);
        $data = json_encode($_POST['invoice_data']);
        $created_by = $_SESSION['user'];
        $no_of_downloads = 1;
        $date = date('Y-m-d H:i:s');
//=================INVOICES DETAILS==============================================
        $sql = "INSERT INTO invoices (invoice_no,data,created_by,no_of_downloads, created_date) VALUES ("
                . "'$invoice_number',"
                . "'$data',"
                . "'$created_by',"
                . "'$no_of_downloads',"
                . "'$date');";
        if (mysqli_query($link, $sql)) {
//=================ADMISSION FORM DETAILS==============================================
            $invoice_id = $link->insert_id;
            $new_sql = "UPDATE admission_form SET invoice_id='$invoice_id' WHERE id=" . $form_id;
            if (mysqli_query($link, $new_sql)) {
                $repayment_date = json_encode((object) (array('invoice_id' => 0, 'invoice_date' => $date, 'created_by' => $_SESSION['user'])));
                $repayment_sql = "INSERT INTO repayment (admission_id,first_invoice_id,total_repayments,data,modified_on,repayment_dates) VALUES ("
                        . "'" . $form_id . "',"
                        . "'$invoice_id',"
                        . "'1',"
                        . "'$data',"
                        . "'$date',"
                        . "'$repayment_date');";
                if (mysqli_query($link, $repayment_sql)) {
                    $link = config();
                    $student_sql = "SELECT COUNT(*) as cnt from admission_form where class=" . $course_info[0] . " AND invoice_id > 0";
                    $student_data = [];
                    if ($student_res = mysqli_query($link, $student_sql)) {
                        while ($admission_student_data = mysqli_fetch_assoc($student_res)) {
                            array_push($student_data, $admission_student_data);
                        }
                        $roll_number = $student_data[0]['cnt'];
                        $temp_count = 0;
                        $t_sql = "UPDATE admission_form SET student_roll_number='" . ($roll_number + $temp_count) . "' WHERE id=" . $form_id;
                        if (mysqli_query($link, $t_sql)) {
                            $student_roll_no = ($roll_number + $temp_count);
                            $t_sql = "UPDATE students SET active_roll_number='" . ($roll_number + $temp_count) . "' WHERE active_admission_id=" . $form_id;
                            if (mysqli_query($link, $t_sql)) {
//                                commit();
                                echo json_encode(array('success' => true, 'message' => 'Thank you! Your information was successfully saved!', 'data' => array('invoice_id' => $invoice_id, 'form_id' => $form_id, 'class' => $class)));
                                die();
                            } else {
                                echo json_encode(array('success' => false, 'message' => 'error in sql', 'data' => []));
                            }
                        } else {
                            echo json_encode(array('success' => false, 'message' => 'error in sql', 'data' => []));
                        }
                    } else {
                        echo json_encode(array('success' => false, 'message' => 'error in sql', 'data' => []));
                    }
                } else {
                    echo json_encode(array('success' => false, 'message' => 'error in sql', 'data' => []));
                }
            } else {
                echo json_encode(array('success' => false, 'message' => 'error in sql', 'data' => []));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'error in sql', 'data' => []));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'error in sql', 'data' => []));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'error in sql', 'data' => []));
}
mysqli_close($link);
