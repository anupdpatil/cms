<?php
if (!isset($_SESSION['user'])) {
    if ($_SERVER['SERVER_NAME'] == 'localhost')
        header('Location: http://localhost/acsc/login.php');
    else
        header('Location: http://' . $_SERVER['SERVER_NAME'] . '/login.php');
}
function config(){
if ($_SERVER['SERVER_NAME'] == 'localhost')
    $link = mysqli_connect("localhost", "root", "", "acsc");    
else
    $link = mysqli_connect("68.178.145.9", "c656u1gpdlj9", "Duryodhan@1", "acsc");

    if ($link === false)
        die("DB ERROR: Could not connect. " . mysqli_connect_error());
return $link;
}
?>