<?php
include __DIR__ . "/header.php";

if (isset($_POST[''])) {
    $winkelwagen = $_SESSION['winkelwagen'];
    $products = loadProducts($winkelwagen, $Connection);
    $allTotal = 0;
    $salesID = 1;
    $contactID = 1;
    $orderDate = date("Y/m/d");
    $deliveryDate = date("Y/m/d");
    $backOrderI = 1;
    $lastEditBY = 1;
    $lastEditDate = date("Y/m/d");

    $allTotal = 0;
    foreach ($products as $product) {
        $gegevens = $_SESSION['login'];

        $productUpdate = mysqli_prepare($Connection, "UPDATE stockitemholdings SET QuantityOnHand = QuantityOnHand-(?) WHERE StockItemID=(?)");
        mysqli_stmt_bind_param($productUpdate, 'ii', $product['aantal'], $product['id']);
        mysqli_stmt_execute($productUpdate);

        $orderInput = mysqli_prepare($Connection, "INSERT INTO orders (CustomerID, SalespersonPersonID, ContactPersonID, OrderDate, 
                                                    ExpectedDeliveryDate, IsUndersupplyBackordered, LastEditedBy, LastEditedWhen) VALUES(?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($orderInput, 'iiissiis', $gegevens['CustomerID'], $salesID, $contactID, $orderDate, $deliveryDate, $backOrderI, $lastEditBY, $lastEditDate);
        mysqli_stmt_execute($orderInput);
        return mysqli_stmt_affected_rows($orderInput) == 1;
    }
}
?>



