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
        'achternaam' => $_POST['achternaam'],
        'postcode' => $_POST['postcode'],
        'huisnummer' => $_POST['huisnummer'],
        'toev' => $_POST['toev'],
        'straat' => $_POST['straat'],
        'plaats' => $_POST['plaats'],
        'email' => $_POST['email'],
        'telefoonnummer' => $_POST['telefoonnummer']
    );

    if(isset($_POST['account_aanmaken'])){
        $_SESSION['AfrekenGegevens']['newAcc'] = TRUE;
        $_SESSION['AfrekenGegevens']['password'] = $_POST['password'];
    }

    header("Location: overzicht.php");
    die();
}

if(isset($_GET['save-change'])){
    $id = $_GET['id'];
    $amount = $_GET['aantal'];
    updateAmount($id, $amount, $_SESSION['winkelwagen']);
    header("Location: winkelmandje.php");
    die();
}

if(isset($_POST['afronden']) && $_GET['afronden'] == 'Bestelling afronden'){
    bestellingAfronden($_SESSION['winkelwagen'], $_SESSION['AfrekenGegevens']);
    header("Location: overzicht.php");
    die();
}


