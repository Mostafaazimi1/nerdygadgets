<?php

include "connect.php";

$conn = new PDO("mysql:host=localhost;dbname=nerdygadgets", 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$action = isset($_GET['action']) ? $_GET['action'] : "";

//Add to cart
if ($action == 'addcart' && $_SERVER['REQUEST_METHOD'] == 'POST') {

    //Finding the product by code
    $query = "SELECT * FROM stockitems WHERE addcart=:addcart";
    $stmt = $conn->prepare($query);
    $stmt->bindParam('StockItemID', $_POST['StockItemID']);
    $stmt->execute();
    $product = $stmt->fetch();

//    $currentQty = $_SESSION['stockitems'][$_POST['addcart']]['qty']+1; //Incrementing the product qty in cart
    $_SESSION['stockitems'][$_POST['addcart']] = array('StockItemName' => $product['StockItemName']);
//    'qty'=>$currentQty,
    $product = '';
    header("Location:view.php?product-added");

    if (TRUE) {
        echo "gelukt";
    } else {
        echo "gefaald";
    }
} else {
    echo "R.I.P";
}
