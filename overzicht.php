<?php
include __DIR__ . "/header.php";

$winkelwagen = $_SESSION['winkelwagen'];
$products = loadProducts($winkelwagen, $Connection);
$afrekenGegevens = $_SESSION['AfrekenGegevens'];

?>

<?php
if(isset($_GET['afronden'])){

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
            //return mysqli_stmt_affected_rows($orderInput) == 1;
        }
        $_SESSION['messageCount2'] = 1;
        print('<meta http-equiv = "refresh" content = "0; url = ./" />');
}
else{
    print("else1");
}

?>



<?php
if (isset($_SESSION["AfrekenGegevens"])) {
    ?>
    <div class="overzicht">
        <h1>Overzicht</h1>
        <div class="overzicht-wrapper">
            <div class="product-overzicht">
                <table>
                    <div class="Naam">
                        <tr>
                            <th>Voornaam</th>
                            <th>Achternaam </th>
                            <th> </th>
                        </tr>
                        <tr>
                            <td><?php echo $afrekenGegevens["voornaam"]?></td>
                            <td><?php echo $afrekenGegevens["achternaam"]?></td>

                        </tr>
                        <tr>
                            <th>Postcode</th>
                            <th>Huisnummer</th>
                            <th> </th>
                        </tr>

                        <tr>
                            <td><?php echo $afrekenGegevens["postcode"]?></td>
                            <td><?php echo $afrekenGegevens["huisnummer"]?></td>
                            <td><?php echo $afrekenGegevens["toev"]?></td>
                        </tr>
                        <tr>
                            <th>Straatnaam</th>
                            <th>Woonplaats</th>
                        </tr>

                        <tr>
                            <td><?php echo $afrekenGegevens["straat"]?></td>
                            <td><?php echo $afrekenGegevens["plaats"]?></td>
                        </tr>
                        <tr>
                            <th>E-mailadress</th>
                        </tr>

                        <tr>
                            <td><?php echo $afrekenGegevens["email"]?></td>
                        </tr>
                        <tr>
                            <th>Telefoonnummer</th>
                        </tr>
                        <tr>
                            <td><?php echo $afrekenGegevens["telefoonnummer"]?></td>
                        </tr>
                    </div>
                </table>

            </div>

            <div class="prijs-overzicht">
                <h2>Prijs overizcht</h2>
                <table>
                    <?php
                    $allTotal = 0;
                    foreach ($products as $product) {
                        $total = $product['price'] * $product['aantal'];
                        $allTotal += $total;
                        echo "<tr>";
                        echo "<td><p>" . $product['name'] . "</p></td>";
                        echo "<td><p>€" . $product['price'] . "</p></td>";
                        echo "<td><p>x" . $product['aantal'] . "</p></td>";
                        echo "</tr>";

                    }
                    ?>
                    <tr>
                        <td>Subtotaal</td>
                        <td class="td-geld table-rechts">€<?php echo $allTotal; ?>,-</td>
                    </tr>
                    <tr>
                        <td>Verzendkosten</td>
                        <td class="td-gratis-verz table-rechts">
                            <?php if ($allTotal < 25) {
                                echo "€6,25";
                            } else {
                                echo 'Gratis';
                            } ?>
                        </td>
                    </tr>
                </table>
                <hr class="betalen-hr">
                <table>
                    <tr>
                        <td>Totaalprijs</td>
                        <td class="td-geld table-rechts"> <?php if ($allTotal < 25) {
                                echo "€" . ($allTotal + 6.25);
                            } else {
                                echo "€" . $allTotal . ",-";
                            } ?></td>
                    </tr>
                </table>
                <p>Inclusief btw</p>
                <form action="overzicht.php" method="get">
                    <input class="bestelling-btn" type="submit" name="afronden" value="Bestelling afronden">
                </form>
            </div>
        </div>
    </div>
<?php
}
else{
    echo '<script type="text/javascript">
           window.location = "afrekenen.php"
      </script>';
}

?>




