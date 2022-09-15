<?php
session_start();
$user_data = [];
    include 'data_manipulator_config.php';
    $link = config();
$sql = "SELECT u.first_name,u.last_name,u.email, u.contact_number,r.role FROM user u JOIN user_role r on r.id=u.role_id";
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
