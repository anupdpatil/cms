<?php
session_start();
$user_data = [];
    include 'data_manipulator_config.php';
    $link = config();
$sql = "SELECT a.*,c.course_name,c.course_group "
        . "FROM admission_form a JOIN course c on c.id=a.class";
$result = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($result, $user_data);
    }
    echo json_encode($result);
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
mysqli_close($link);
?>
