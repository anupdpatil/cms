<?php
session_start();
session_destroy();
if ($_SERVER['SERVER_NAME'] == 'localhost')
    header('Location: http://' . $_SERVER['SERVER_NAME'] . '/acsc');
else
    header('Location: http://' . $_SERVER['SERVER_NAME'].'/');
