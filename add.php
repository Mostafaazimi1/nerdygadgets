<?php
include "functions.php";

if (isset($_POST["action"])) {
    session_start();

    $productID = $_POST["addcart"];
    $aantal = $_POST["aantal"];
    $maxaantal = $_POST["max-aantal"];

    if($aantal >= 1){
        $total = addItem($productID, $aantal, $maxaantal);
    }

    header("Location: view.php?id=" . $productID . "&amount=". $total ."&succes=true");
    die();
}

