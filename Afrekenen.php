<?php
include __DIR__ . "/header.php";

$winkelwagen = $_SESSION['winkelwagen'];
$products = loadProducts($winkelwagen, $Connection);

$voornaam = "";
$tussenvoegsel = "";
$achternaam = "";
$postcode = "";
$huisnummer = "";
$toev = "";
$straat = "";
$plaats = "";
$email = "";
$telefoonnummer = "";


?>

<?php
if (isset($_SESSION["login"])) {
$gegevens = $_SESSION['login'];
print_r($_SESSION['login']);
    $HuisnummerStraat = (explode(" ",$_SESSION['DeliveryAddressLine2']));
    $voornaam = $gegevens["PreferredName"];
    $achternaam = (str_replace($gegevens['PreferredName']." ", "", $gegevens['FullName']));
    $postcode = $gegevens[''];
    $huisnummer = $HuisnummerStraat[1];
    $toev = $gegevens[''];
    $straat = $HuisnummerStraat[2];
    $plaats = $gegevens[''];
    $email = $gegevens['EmailAddress'];
    $telefoonnummer = $gegevens['PhoneNumber'];

}


?>

<form method="post" action="Afrekenen.php">
    <div class="Bestelgegevens">
        <h1>Bestelgegevens</h1>
        <div class="overzicht-wrapper">
            <div class="product-overzicht">
                <table>
                    <div class="Naam">
                        <tr>
                            <th>Naam</th>
                        </tr>


                        <tr>
                            <td><input type="text" id="voornaam" name="voornaam" placeholder="Voornaam"
                                       value="<?php echo($voornaam); ?>" required></td>
                            <td><input type="text" id="achternaam" name="achternaam" placeholder="Achternaam"
                                       value="<?php echo($achternaam); ?>" required></td>
                        </tr>
                        <tr>
                            <th>Postcode</th>
                            <th>Huisnummer</th>
                        </tr>
                        <tr>
                            <td><input type="text" id="postcode" name="postcode" placeholder="1234AA"
                                       value="<?php echo($postcode); ?>" required></td>
                            <td><input type="text" id="huisnummer" name="huisnummer" placeholder="Nr."
                                       value="<?php echo($huisnummer); ?>" required></td>
                            <td><input type="text" id="toev" name="toev" placeholder="Toev."
                                       value="<?php echo($toev); ?>"></td>
                        </tr>
                        <tr>
                            <th>Straatnaam</th>
                            <th>Woonplaats</th>
                        </tr>

                        <tr>
                            <td><input type="text" id="straatnaam" name="straat" placeholder="Straat"
                                       value="<?php echo($straat); ?> " required></td>
                            <td><input type="text" id="woonplaats" name="plaats" placeholder="Plaats"
                                       value="<?php echo($plaats); ?>" required></td>
                        </tr>
                        <tr>
                            <th>E-mailadress</th>
                        </tr>
                        <tr>
                            <td><input type="text" id="Email" name="email" placeholder="voorbeeld@voorbeeld.nl"
                                       value="<?php echo($email); ?>" required></td>
                        </tr>
                        <tr>
                            <th>Telefoonnummer</th>
                        </tr>
                        <tr>
                            <td><input type="text" id="telefoonnummer" name="telefoonnummer" placeholder="06 12345678"
                                       value="<?php echo($telefoonnummer); ?>" required></td>
                        </tr>
                        <?php if (isset($_SESSION["login"])) {
                        } else {//login blokje
                            echo "<tr>";
                            echo "<div>";

                            echo "<tr>";
                            echo "<th>";
                            echo "<td><input type='checkbox' name='account_aanmaken' value='ja'></td>";
                            echo "<td>account aanmaken</td>";
                            echo "</th>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<th>";
                            echo "<td><input type='password' placeholder='Wachtwoord' name='password' autocomplete='new-password'></td>";
                            echo "<td><input type='password' placeholder='Bevestig wachtwoord' name='confirmpassword' autocomplete='new-password'></td>";
                            echo "</th>";
                            echo "</tr>";
                            echo "</div>";
                            echo "</tr>";
                        }
                        ?>
                    </div>
                </table>

            </div>
            <?php
            $allTotal = 0;
            foreach ($products as $product) {
                $total = $product['price'] * $product['aantal'];
                $allTotal += $total;
            }
            ?>
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

                <input type="submit" name="Afreken_submit" value="Afrekenen"
            </div>
        </div>
    </div>
</form>

