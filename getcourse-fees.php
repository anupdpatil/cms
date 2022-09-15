<?php

session_start();
$user_data = [];
include 'data_manipulator_config.php';
$link = config();

if (isset($_GET['id'])) {
    $sql = "SELECT "
            . "fees_breakup as course_headers "
            . "FROM  course WHERE id=" . $_GET['id'];
    $result = [];
    if ($res = mysqli_query($link, $sql)) {
        while ($user_data = mysqli_fetch_assoc($res)) {
            array_push($result, $user_data);
        }
        $sql = "SELECT CONCAT(first_name,' ', middle_name,' ', last_name) as student_name FROM  students WHERE active_class=" . $_GET['id'];
        $students = [];
        if ($res = mysqli_query($link, $sql)) {
            while ($user_data = mysqli_fetch_assoc($res)) {
                array_push($students, $user_data);
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
        $result['student_list'] = $students;
        echo json_encode($result);
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
} else {
    echo json_encode([]);
}
mysqli_close($link);
?>
