<?php

session_start();
$user_data = [];
    include 'data_manipulator_config.php';
    $link = config();
$sql = "DELETE from admission_form WHERE id='" . $_GET['form'] . "'";
if ($res = mysqli_query($link, $sql)) {
    $sql = "DELETE from repayment WHERE id='" . $_GET['form'] . "'";
    if ($res = mysqli_query($link, $sql)) {
        $sql = "DELETE from invoices WHERE id IN (select invoice_id from admission_form WHERE id='" . $_GET['form'] . "')";
        if ($res = mysqli_query($link, $sql)) {
            echo json_encode($res);
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
mysqli_close($link);
?>
