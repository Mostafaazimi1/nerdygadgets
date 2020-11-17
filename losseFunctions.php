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
'voornaam' => $_POST['Afreken_submit'],
'tussenvoegsel' => $_POST['Afreken_submit'],
'achternaam' => $_POST['Afreken_submit'],
'postcode' => $_POST['Afreken_submit'],
'huisnummer' => $_POST['Afreken_submit'],
'toev' => $_POST['Afreken_submit'],
'straat' => $_POST['Afreken_submit'],
'plaats' => $_POST['Afreken_submit'],
'email' => $_POST['Afreken_submit'],
'telefoonnummer' => $_POST['Afreken_submit']
);

header("Location: overzicht.php");
die();
}