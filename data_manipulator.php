<?php
    include 'data_manipulator_config.php';
    $link = config();
    $query = "select * from user";
    if ($res = mysqli_query($link, $query)) {
        print_r($res);
    } else {
        die("ERROR: Could not able to execute $query. " . mysqli_error($link));
    }