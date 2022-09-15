<?php

session_start();
include 'data_manipulator_config.php';
$link = config();
if (!isset($_GET['form_id'])) {
    die("please send appropriate request");
}
$form_id = $_GET['form_id'];

$sql = "INSERT INTO students (active_class,active_admission_id,first_name,middle_name,last_name,prn_number,phone_nmuber,email,birth_date,gender,religion,category,
handicap,address,aadhar_number,created_by,active_roll_number,user_image,created_date,modified_date)
SELECT ad.class,ad.id,ad.first_name,ad.middle_name,ad.last_name,ad.prn_number,ad.phone_nmuber,ad.email,ad.dob,ad.gender,ad.religion,ad.category,ad.handicap,ad.address,ad.aadhar_number,ad.created_by,ad.student_roll_number,ad.user_image,ad.created_date,ad.modified_date FROM admission_form ad WHERE ad.id = '$form_id'";
if (mysqli_query($link, $sql)) {
    echo json_encode(array('success' => true, 'message' => 'Data inserted', 'data' => array('student_id' => $link->insert_id)));
} else {
    echo json_encode(array('success' => false, 'message' => 'Data not inserted', 'data' => []));
}
mysqli_close($link);
die();
