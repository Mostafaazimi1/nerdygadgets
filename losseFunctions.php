<?php
if (!isset($_SESSION['winkelwagen'])) {
$_SESSION['winkelwagen'] = array();
}
$winkelwagen = $_SESSION['winkelwagen'];

if (isset($_GET['delete'])) {
$winkelwagen = deleteProduct($winkelwagen, (int)$_GET['delete']);
header("Refresh:0; url=winkelmandje.php");
}

if (isset($_POST['Afreken_submit'])) {
$_SESSION['AfrekenGegevens'] = array(
'voornaam' => $_POST['voornaam'],
'tussenv' => $_POST['tussenv'],
'achternaam' => $_POST['achternaam'],
'postcode' => $_POST['postcode'],
'huisnummer' => $_POST['huisnummer'],
'toev' => $_POST['toev'],
'straat' => $_POST['straat'],
'plaats' => $_POST['plaats'],
'email' => $_POST['email'],
'telefoonnummer' => $_POST['telefoonnummer']
);

header("Location: overzicht.php");
die();
}