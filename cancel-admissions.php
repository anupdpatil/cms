<?php

session_start();
include 'data_manipulator_config.php';
$link = config();
$form_id = $_GET['form_id'];

$sql = "INSERT into cancelled_invoices (SELECT * from invoices where id = (SELECT invoice_id FROM admission_form WHERE id ='$form_id'))";
if ($res = mysqli_query($link, $sql)) {
    $sql = "DELETE from invoices where id = (select invoice_id from admission_form where id= '$form_id')";
    if ($res = mysqli_query($link, $sql)) {
        $sql = "INSERT into cancelled_students (SELECT * from students where active_admission_id = '$form_id')";
        if ($res = mysqli_query($link, $sql)) {
            $sql = "DELETE from students where active_admission_id = '$form_id'";
            if ($res = mysqli_query($link, $sql)) {
                $sql = "INSERT into cancelled_repayment (SELECT * from repayment where admission_id = '$form_id')";
                if ($res = mysqli_query($link, $sql)) {
                    $sql = "DELETE from repayment where admission_id = '$form_id'";
                    if ($res = mysqli_query($link, $sql)) {
                        $sql = "INSERT into cancelled_admission_form (SELECT * from admission_form where id = '$form_id')";
                        if ($res = mysqli_query($link, $sql)) {
                            $sql = "DELETE from admission_form where id = '$form_id'";
                            if ($res = mysqli_query($link, $sql)) {
                                echo json_encode(array('success' => TRUE, 'message' => 'Admission cancelled'));
                            } else {
                                echo json_encode(array('success' => FALSE, 'message' => "ERROR: Could not able to execute '$sql' " . mysqli_error($link)));
                            }
                        } else {
                            echo json_encode(array('success' => FALSE, 'message' => "ERROR: Could not able to execute '$sql' " . mysqli_error($link)));
                        }
                    } else {
                        echo json_encode(array('success' => FALSE, 'message' => "ERROR: Could not able to execute '$sql' " . mysqli_error($link)));
                    }
                } else {
                    echo json_encode(array('success' => FALSE, 'message' => "ERROR: Could not able to execute '$sql' " . mysqli_error($link)));
                }
            } else {
                echo json_encode(array('success' => FALSE, 'message' => "ERROR: Could not able to execute '$sql' " . mysqli_error($link)));
            }
        } else {
            echo json_encode(array('success' => FALSE, 'message' => "ERROR: Could not able to execute '$sql' " . mysqli_error($link)));
        }
    } else {
        echo json_encode(array('success' => FALSE, 'message' => "ERROR: Could not able to execute '$sql' " . mysqli_error($link)));
    }
} else {
    echo json_encode(array('success' => FALSE, 'message' => "ERROR: Could not able to execute '$sql' " . mysqli_error($link)));
}
mysqli_close($link);
