<?php
//if (isset ($_SESSION['login']) == true){
//    $Medewerkerlogin = $_SESSION['login'];
//    if ($Medewerkerlogin['IsSalesperson'] == 1){
//        $host = "localhost";
//        $user = "medewerker";
//        $password = "WO869N0a3ImlyC3Jsga";
//        $database = "nerdygadgets";
//    } elseif ($Medewerkerlogin['IsSalesperson'] == 0){
//        $host = "localhost";
//        $user = "geregistreerd";
//        $password = "I3U7rpDAlwT7DGKh3eH";
//        $database = "nerdygadgets";
//    }
//} else {
//    $host = "localhost";
//    $user = "Bezoeker";
//    $password = "YO4vQRA3a8Kda4jb";
//    $database = "nerdygadgets";
//}
//
//$host = "localhost";
//$user = "Bezoeker";
//$password = "YO4vQRA3a8Kda4jb";
//$database = "nerdygadgets";

$host = "localhost";
$user = "geregistreerd";
$password = "I3U7rpDAlwT7DGKh3eH";
$database = "nerdygadgets";


//$host = "localhost";
//$user = "root";
//$password = "";
//$database = "nerdygadgets";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
try {
    $Connection = mysqli_connect($host, $user, $password, $database);
    mysqli_set_charset($Connection, 'latin1');
    $DatabaseAvailable = true;
} catch (mysqli_sql_exception $e) {
    $DatabaseAvailable = false;
}
if (!$DatabaseAvailable) {
    ?><h2>Website wordt op dit moment onderhouden.</h2><?php
    die();
}

