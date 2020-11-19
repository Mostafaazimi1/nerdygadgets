<?php
if (isset($_POST[''])) { //submit afronden?
$products = loadProducts($winkelwagen, $Connection);
$allTotal = 0;

foreach ($products as $product) {
$total = $product['price'] * $product['aantal'];
$allTotal += $total;

$statement = mysqli_prepare($connection, "UPDATE stockitemholdings SET QuantityOnHand = QuantityOnHand-(?) WHERE StockItemID=(?)");
mysqli_stmt_bind_param($statement, 'ii', $product['aantal'], $product['id'] );
mysqli_stmt_execute($statement);
return mysqli_stmt_affected_rows($statement) == 1;
}
?>


