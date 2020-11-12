<?php
if(isset($_POST["action"]))
{
    $productID = $_POST["addcart"];
    $aantal = $_POST["aantal"];

    $winkelwagen = array();
    $winkelwagen[$productID] = $aantal;

    $quantity = 0;
    $quantityNow = $quantity + $aantal;

    header("Location: view.php?id=" . $productID . "&succes=true");
    die();
}