<?php
include __DIR__ . "/header.php";

$winkelwagen = $_SESSION['winkelwagen'];
$products = loadProducts($winkelwagen, $Connection);
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
                        <td><input type="text" id="voornaam" name="voornaam" placeholder="Voornaam"></td>
                        <td><input type="text" id="tussenv" name="tussenv" placeholder="Tussenv."></td>
                        <td><input type="text" id="achternaam" name="achternaam" placeholder="Achternaam"></td>
                    </tr>
                    <tr>
                        <th>Postcode</th>
                        <th>Huisnummer</th>
                        <th> </th>
                    </tr>

                    <tr>
                        <td><input type="text" id="postcode" name="postcode" placeholder="1234AA"></td>
                        <td><input type="text" id="huisnummer" name="huisnummer" placeholder="Nr."></td>
                        <td><input type="text" id="toev" name="toev" placeholder="Toev."></td>
                    </tr>


                    <tr>
                        <th>Straatnaam</th>
                        <th>Woonplaats</th>
                    </tr>

                    <tr>
                        <td><input type="text" id="straatnaam" name="straatnaam" placeholder="Straat"></td>
                        <td><input type="text" id="woonplaats" name="woonplaats" placeholder="Plaats"></td>
                    </tr>


                    <tr>
                        <th>E-mailadress</th>
                    </tr>

                    <tr>
                        <td><input type="text" id="Email" name="Email" placeholder="voorbeeld@voorbeeld.nl"></td>
                    </tr>


                    <tr>
                        <th>Telefoonnummer</th>
                    </tr>

                    <tr>
                        <td><input type="text" id="telefoonnummer" name="telefoonnummer" placeholder="06 12345678"></td>
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






