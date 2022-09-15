<?php
session_start();
include 'data_manipulator_config.php';
$link = config();

if ($_SERVER['SERVER_NAME'] == 'localhost') {
//    $link = mysqli_connect("localhost", "root", "", "acsc");    
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
} else {
//    $link = mysqli_connect("68.178.145.9", "c656u1gpdlj9", "Duryodhan@1", "acsc");
    $dbhost = '68.178.145.9';
    $dbuser = 'c656u1gpdlj9';
    $dbpass = 'Duryodhan@1';
}
$dbname = 'acsc';

$dir_1 = dirname(__FILE__) . '/table-'. $dbname .'.sql';

exec("mysqldump --user={$dbuser} --password={$dbpass} --host={$dbhost} {$dbname} --result-file={$dir_1} 2>&1", $output_1);

var_dump($output_1);
?>