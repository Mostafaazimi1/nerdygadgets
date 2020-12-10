<?php
include 'functions.php';
include 'connect.php';

$info = [
    'temperatuur' => getTemprature($Connection, 5)
];

echo json_encode($info);