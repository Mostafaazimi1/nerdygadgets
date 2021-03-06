<?php
include 'header.php';

//$winkelwagen = $_SESSION['winkelwagen'];

$products = loadProducts($_SESSION['winkelwagen'], $Connection);


// Code from header
if (isset($_GET['delete'])) {
    $_SESSION['winkelwagen'] = deleteProduct($_SESSION['winkelwagen'], (int)$_GET['delete']);
    print('<meta http-equiv = "refresh" content = "0; url = ./winkelmandje.php" />');
}

// Code from header
if (isset($_GET['save-change'])) {
    $id = $_GET['id'];
    $amount = $_GET['aantal'];
    updateAmount($id, $amount, $_SESSION['winkelwagen']);
    print('<meta http-equiv = "refresh" content = "0; url = ./winkelmandje.php" />');
}
?>


    <div class="winkelmandje">
    <h1>Winkelmandje</h1>
<?php
if (!$products) {
    echo "<p style='font-size: 20px; margin-top: 24px;'>Je winkelwagen is momenteel leeg.</p>";
} else {
    ?>
    <div class="overzicht-wrapper">
        <div class="product-overzicht">
            <table>
                <tr>
                    <th>Foto</th>
                    <th>Title</th>
                    <th>Prijs</th>
                    <th>Aantal</th>
                    <th></th>
                    <th>Subtotaal</th>
                    <th>Verwijderen</th>
                </tr>

                <?php
                $allTotal = 0;
                foreach ($products as $product) {
                    $total = (($product['price']*$product['kortingc'] )* $product['aantal']);
                    $allTotal += $total;
                    echo "<tr>";
                    // if statement voor winkewagen categoriepicture
                    echo "<td><img src='Public/StockItemIMG/" . $product['img'] . "' style='max-width: 100px'></td>";
                    echo "<td><p>" . $product['name'] . "</p></td>";
                    echo "<td><p>€" . $product['price'] . "</p></td>";
                    echo "<form action='winkelmandje.php' type='GET'>";
                    echo "<td><input type='number' min='0' max='" . $product['aantalbeschikbaar'] . "' name='aantal' value='" . $product['aantal'] . "' style='width: 55px;height: 40px;'></td>";
                    echo "<input type='hidden' name='id' value='" . $product['id'] . "'>";
                    echo "<td><input type='submit' name='save-change' value='opslaan'></td>";
                    echo "</form>";
                    echo "<td><p>€" . number_format($total, 2) . "</p></td>";
                    echo "<td><a href='winkelmandje.php?delete=" . $product['id'] . "'>X</a></td>";
                    echo "</tr>";
                }
                $allTotal = number_format((float)$allTotal, 2, '.', '');
                ?>

            </table>
        </div>

        <div class="prijs-overzicht">
            <h2>Prijs overizcht</h2>
            <table>
                <tr>
                    <td>Subtotaal</td>
                    <td class="td-geld table-rechts">€<?php echo number_format($allTotal, 2); ?></td>
                </tr>
                <tr>
                    <td>Verzendkosten</td>
                    <td class="td-gratis-verz table-rechts">
                        <?php if ($allTotal < 25) {
                            echo "€6.25";
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
                            echo "€" . number_format(($allTotal + 6.25), 2);
                        } else {
                            echo "€" . number_format($allTotal, 2);
                        } ?></td>
                </tr>
            </table>
            <p>Inclusief btw</p>
            <form action="afrekenen.php" method="post">
                <input type="submit" value="Bestelling afronden">
            </form>
        </div>
    </div>
    </div>
<?php } ?>