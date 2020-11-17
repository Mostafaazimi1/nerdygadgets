<?php
include __DIR__ . "/header.php";

$winkelwagen = $_SESSION['winkelwagen'];
$products = loadProducts($winkelwagen, $Connection);
$afrekenGegevens = $_SESSION['AfrekenGegevens'];
?>
<?php
if (isset($_SESSION["AfrekenGegevens"])) {
    ?>
    <div class="overzicht">
        <h1>Bestelgegevens</h1>
        <div class="overzicht-wrapper">
            <div class="product-overzicht">
                <table>
                    <div class="Naam">
                        <tr>
                            <th>Naam</th>
                            <th> </th>
                            <th> </th>
                        </tr>
                        <tr>
                            <td><?php echo $afrekenGegevens["voornaam"]?></td>
                            <?php if ($afrekenGegevens["tussenv"]==""){
                                echo"<td>" . $afrekenGegevens['achternaam'] . "</td>";
                            }
                            else{
                                echo"<td>" . $afrekenGegevens['tussenv'] . "</td>";
                                echo"<td>" . $afrekenGegevens['achternaam'] . "</td>";
                            }//of achternaam op de plek van tussenvoegsel kan staan als tussenvoegsel leeg is.
                            ?>
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
                <button class="bestelling-btn">Bestelling afronden</button>
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




