<?php
include 'header.php';

$winkelwagen = $_SESSION['winkelwagen'];

$products = loadProducts($winkelwagen, $Connection);
?>


<div class="winkelmandje">
    <h1>Winkelmandje</h1>
    <?php
    if(!$products){
        echo "Je winkelwagen is momenteel leeg.";
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
                    <th>Subtotaal</th>
                    <th>Verwijderen</th>
                </tr>

                <?php
                $allTotal = 0;
                foreach ($products as $product) {
                    $total = $product['price'] * $product['aantal'];
                    $allTotal += $total;
                    echo "<tr>";
                    echo "<td><img src='Public/StockItemIMG/" . $product['img'] . "' style='max-width: 30px'></td>";
                    echo "<td><p>" . $product['name'] . "</p></td>";
                    echo "<td><p>€" . $product['price'] . "</p></td>";
                    echo "<td><p>" . $product['aantal'] . "</p></td>";
                    echo "<td><p>€" . number_format($total, 2) . "</p></td>";
                    echo "<td><a href='winkelmandje.php?delete=" . $product['id'] . "'>X</a></td>";
                    echo "</tr>";
                }
                ?>

            </table>
        </div>

        <div class="prijs-overzicht">
            <h2>Prijs overizcht</h2>
            <table>
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
            <form action="Afrekenen.php" method="post">
                <input type="submit" value="Bestelling afronden">
            </form>
        </div>
    </div>
</div>
<?php } ?>