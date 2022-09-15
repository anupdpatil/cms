<?php
session_start();
    include 'data_manipulator_config.php';
    $link = config();
$user_data = [];
$sql = "SELECT * from admission_form where id=" . $_GET['form'];
$result = [];
if ($res = mysqli_query($link, $sql)) {
    while ($user_data = mysqli_fetch_assoc($res)) {
        array_push($result, $user_data);
    }
    echo '<pre>';
    print_r($result);
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
mysqli_close($link);
?>
