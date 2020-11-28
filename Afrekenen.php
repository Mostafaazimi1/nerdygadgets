<?php
include __DIR__ . "/header.php";

$winkelwagen = $_SESSION['winkelwagen'];
$products = loadProducts($winkelwagen, $Connection);

// gegevens van de niet ingelogde klant worden bewaard in de database table customers
// eerst opvragen customerid die hoort bij session -- customerid is gelinkt met customerid van table orders

// Gebruiker is niet ingelogd
        $FirstName = "";
        $tussenvoegsel = "";
        $LastName = "";
        $postcode = "";
        $HouseNumber = "";
        $toev = "";
        $StreetName = "";
        $Plaats = "";
        $email = "";
        $PhoneNumber = "";

if(isset($_POST["Afreken_submit"])) {
    if (isset($_SESSION["login"])) {
        // Gebruiker is ingelogd
        $gegevens = $_SESSION['login'];
        $HuisnummerStraat = (explode(" ", $gegevens['DeliveryAddressLine2']));
        $FirstName = $gegevens["PreferredName"];
        $LastName = (str_replace($gegevens['PreferredName'] . " ", "", $gegevens['FullName']));
        $postcode = $gegevens['DeliveryPostalCode'];
        $HouseNumber = $HuisnummerStraat[0];
        $toev = "";
        $StreetName = $HuisnummerStraat[1];
        $Plaats = $gegevens['PostalAddressLine2'];
        $email = $gegevens['EmailAddress'];
        $HouseNumber = $gegevens['PhoneNumber'];
        $Plaats = ucfirst($_POST["Plaats"]);
    }
    //KOMT $plaats VOOR IN COLUMN CITYNAME VAN TABEL CITIES ZO JA RETURN COLUMN VALUE VAN CITYID EN GEEF DEZE AAN $DeliveryCityId
    // ANDERS AFBREKEN
    $sql = "SELECT CityName FROM cities WHERE CityName = '" . $Plaats . "' LIMIT 1";
    $result = $Connection->query($sql);
    $aantalresult = mysqli_num_rows($result);
    if ($aantalresult < 1) {
        echo("Sorry, in " . $Plaats . " leveren wij niet, voer alsjeblieft een nieuw adres in.");
        // MOET ERBIJ: GEGEVENS OPSLAAN BIJ FOUT
    } else {
        $DeliveryCityName = $Plaats;
        $password = $_POST["password"];
        $email = $_POST["email"];
        $PhoneNumber = $_POST["PhoneNumber"];
        $postcode = $_POST["postcode"];
        $FirstName = $_POST["FirstName"];
        $LastName = $_POST["LastName"];
        $HouseNumber = $_POST["HouseNumber"];
        $StreetName = $_POST["StreetName"];
        $CurrentDate = date("Y/m/d");
        $FullName = ($FirstName . " " . $LastName);
        $BuyingGroupId = 1;
        $PrimaryContactPersonId = 9;
        $AlternateContactPersonId = 9;
        $CustomerCategoryId = 1;
        $CreditLimit = 0.00;
        $StandardDiscountPercentage = 0.000;
        $zero = 0;
        $seven = 7;
        $three = 3;
        $NULL = NULL;
        $unknown = "unknown";
        $address = ($HouseNumber . " " . $StreetName);
        $ValidTo = "9999-12-31";
        $BillToCustomerId = 1;
        $DeliveryCityId = 1;
        $LastEditedBy = 1;
        $IsPermittedToLogon = 1;
        $IsExternalLogonProvider = 1;
        $IsSystemUser = 0;
        $IsEmployee = 0;
        $IsSalesPerson = 0;
        $empty = "";
        //Als verbinding gesloten is, wordt de SQL query voorbereid.
        // GEGEVENS IN CUSTOMERS                    faxnumber = string
        $stmt = $Connection->prepare(
            "INSERT INTO customers (CustomerName, BillToCustomerID, CustomerCategoryID, BuyingGroupID, PrimaryContactPersonID,
                                AlternateContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID, CreditLimit,
                                AccountOpenedDate, StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays,
                                PhoneNumber, FaxNumber, DeliveryRun, RunPosition, WebsiteURL, DeliveryAddressLine1,
                                DeliveryAddressLine2, DeliveryPostalCode, DeliveryLocation, PostalAddressLine1, PostalAddressLine2,
                                PostalPostalCode, LastEditedBy, ValidFrom, ValidTo)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiiiiiiidsdiiissssssssssssiss", $FullName, $BillToCustomerId, $CustomerCategoryId, $BuyingGroupId, $PrimaryContactPersonId,
            $AlternateContactPersonId, $three, $DeliveryCityId, $DeliveryCityId, $CreditLimit, $CurrentDate, $StandardDiscountPercentage, $zero, $zero, $seven,
            $PhoneNumber, $zero, $NULL, $NULL, $zero, $unknown, $address,
            $postcode, $unknown, $unknown, $DeliveryCityName, $postcode, $LastEditedBy, $CurrentDate,
            $ValidTo);
        $execval = $stmt->execute();
        echo $execval;
        //echo "Customer gegevens zijn succesvol toegevoegd aan database!";
        $stmt->close();
    }

    // VRAAG VALUE VAN CUSTOMERID UIT CUSTOMERS EN GEEF DEZE EIGEN VARIABELEN -zodat je ze in people tabel kan inserten!
    $sql = "SELECT CustomerID FROM customers WHERE CustomerName = ('$FullName') AND DeliveryPostalCode =
            ('$postcode') AND DeliveryAddressLine2 = ('$address')";
    $result = $Connection->query($sql);
    $row = mysqli_fetch_array($result);
    $CustomerNUM = reset($row);

    //      Als verbinding gesloten is, wordt de SQL query voorbereid.
    if(isset($_POST["account_aanmaken"]) AND isset($_POST["password"]) AND isset($_POST["confirmpassword"])) {
        if(($_POST['account_aanmaken'] == 'ja') AND (($_POST["password"]) != ($_POST["confirmpassword"]))) {
            // GEGEVENS IN PEOPLE image(Photo) = blob
            $stmt1 = $Connection->prepare("insert into people(FullName, PreferredName, SearchName, IsPermittedToLogon, LogonName,
                            IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson,
                            UserPreferences, PhoneNumber, FaxNumber, EmailAddress, Photo, CustomFields,
                            OtherLanguages, LastEditedBy, ValidFrom, ValidTo, CustomerNUM)
                            values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param("sssisisiiissssbssisss", $FullName, $FirstName, $FullName, $IsPermittedToLogon, $email,
                $IsExternalLogonProvider, $password, $IsSystemUser, $IsEmployee, $IsSalesPerson, $empty,
                $PhoneNumber, $empty, $email, $empty, $empty, $empty, $LastEditedBy, $CurrentDate, $ValidTo, $CustomerNUM);
            $execval = $stmt1->execute();
            echo $execval;
            //echo "People gegevens zijn succesvol toegevoegd aan database!";
            $stmt1->close();
        } else {
            // De wachtwoorden moeten overeenkomen! Maak hier code zodat gebruiker terug wordt verwezen en value gevult wordt
        }
    }
    // UPDATEN BILLTOCUSTOMERID IN TABLE customers
    $query = "UPDATE customers SET BillToCustomerID = ? WHERE CustomerID = ?";
    $Statement = mysqli_prepare($Connection, $query);
    mysqli_stmt_bind_param($Statement, "ii", $CustomerNUM, $CustomerNUM);
    mysqli_stmt_execute($Statement);
}


?>
<form method="post" action="afrekenen.php">
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
                            <td><input type="text" id="voornaam" name="FirstName" placeholder="Voornaam"
                                       value="<?php echo($FirstName); ?>" required></td>
                            <td><input type="text" id="achternaam" name="LastName" placeholder="Achternaam"
                                       value="<?php echo($LastName); ?>" required></td>
                        </tr>
                        <tr>
                            <th>Postcode</th>
                            <th>Huisnummer</th>
                        </tr>
                        <tr>
                            <td><input type="text" id="postcode" name="postcode" placeholder="1234AA"
                                       value="<?php echo($postcode); ?>" required></td>
                            <td><input type="text" id="huisnummer" name="HouseNumber" placeholder="Nr."
                                       value="<?php echo($HouseNumber); ?>" required></td>
                            <td><input type="text" id="toev" name="toev" placeholder="Toev."
                                       value="<?php echo($toev); ?>"></td>
                        </tr>
                        <tr>
                            <th>Straatnaam</th>
                            <th>Woonplaats</th>
                        </tr>

                        <tr>
                            <td><input type="text" id="straatnaam" name="StreetName" placeholder='Straat'
                                       value="<?php echo($StreetName); ?> " required></td>
                            <td><input type="text" id="woonplaats" name="Plaats" placeholder="Plaats"
                                       value="<?php echo($Plaats); ?>" required></td>
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
                            <td><input type="text" id="telefoonnummer" name="PhoneNumber" placeholder="06 12345678"
                                       value="<?php echo($PhoneNumber); ?>" required></td>
                        </tr>
                        <?php if(!isset($_SESSION["login"])) {
                            echo "<div>";
                                echo "<tr>";
                                    echo "<th></th>";
                                    echo "<td><input type='checkbox' name='account_aanmaken' value='ja'></td>";
                                    echo "<td>account aanmaken</td>";
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<th></th>";
                                    echo "<td><input type='password' placeholder='Wachtwoord' name='password' autocomplete='new-password'></td>";
                                    echo "<td><input type='password' placeholder='Bevestig wachtwoord' name='confirmpassword' autocomplete='new-password'></td>";
                                echo "</tr>";
                            echo "</div>";
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
                        <td class="td-geld table-rechts">
                            <?php if ($allTotal < 25) {
                                echo "€" . ($allTotal + 6.25);
                            } else {
                                echo "€" . $allTotal . ",-";
                            } ?>
                        </td>
                    </tr>
                </table>
                <p>Inclusief btw</p>

                <input type="submit" name="Afreken_submit" value="Afrekenen">
            </div>
        </div>
    </div>
</form>
<?php
include __DIR__ . "/header.php";

$winkelwagen = $_SESSION['winkelwagen'];
$products = loadProducts($winkelwagen, $Connection);

// gegevens van de niet ingelogde klant worden bewaard in de database table customers
// eerst opvragen customerid die hoort bij session -- customerid is gelinkt met customerid van table orders

if(isset($_POST["Afreken_submit"])) {
    if (isset($_SESSION["login"])) {
        // Gebruiker is ingelogd
        $gegevens = $_SESSION['login'];
        $HuisnummerStraat = (explode(" ", $gegevens['DeliveryAddressLine2']));
        $FirstName = $gegevens["PreferredName"];
        $LastName = (str_replace($gegevens['PreferredName'] . " ", "", $gegevens['FullName']));
        $postcode = $gegevens['DeliveryPostalCode'];
        $HouseNumber = $HuisnummerStraat[0];
        $toev = "";
        $StreetName = $HuisnummerStraat[1];
        $Plaats = $gegevens['PostalAddressLine2'];
        $email = $gegevens['EmailAddress'];
        $HouseNumber = $gegevens['PhoneNumber'];
        $Plaats = ucfirst($_POST["Plaats"]);
    } else {
        // Gebruiker is niet ingelogd
        $FirstName = "";
        $tussenvoegsel = "";
        $LastName = "";
        $postcode = "";
        $HouseNumber = "";
        $toev = "";
        $StreetName = "";
        $Plaats = "";
        $email = "";
        $PhoneNumber = "";
    }
    //KOMT $plaats VOOR IN COLUMN CITYNAME VAN TABEL CITIES ZO JA RETURN COLUMN VALUE VAN CITYID EN GEEF DEZE AAN $DeliveryCityId
    // ANDERS AFBREKEN
    $sql = "SELECT CityName FROM cities WHERE CityName = '" . $Plaats . "' LIMIT 1";
    $result = $Connection->query($sql);
    $aantalresult = mysqli_num_rows($result);
    if ($aantalresult < 1) {
        echo("Sorry, in " . $Plaats . " leveren wij niet, voer alsjeblieft een nieuw adres in.");
        // MOET ERBIJ: GEGEVENS OPSLAAN BIJ FOUT
    } else {
        $DeliveryCityName = $Plaats;
        $password = $_POST["password"];
        $email = $_POST["email"];
        $PhoneNumber = $_POST["PhoneNumber"];
        $postcode = $_POST["PostCode"];
        $FirstName = $_POST["FirstName"];
        $LastName = $_POST["LastName"];
        $HouseNumber = $_POST["HouseNumber"];
        $StreetName = $_POST["StreetName"];
        $CurrentDate = date("Y/m/d");
        $FullName = ($FirstName . " " . $LastName);
        $BuyingGroupId = 1;
        $PrimaryContactPersonId = 9;
        $AlternateContactPersonId = 9;
        $CustomerCategoryId = 1;
        $CreditLimit = 0.00;
        $StandardDiscountPercentage = 0.000;
        $zero = 0;
        $seven = 7;
        $three = 3;
        $NULL = NULL;
        $unknown = "unknown";
        $address = ($HouseNumber . " " . $StreetName);
        $ValidTo = "9999-12-31";
        $BillToCustomerId = 1;
        $DeliveryCityId = 1;
        $LastEditedBy = 1;
        $IsPermittedToLogon = 1;
        $IsExternalLogonProvider = 1;
        $IsSystemUser = 0;
        $IsEmployee = 0;
        $IsSalesPerson = 0;
        $empty = "";
        //Als verbinding gesloten is, wordt de SQL query voorbereid.
        // GEGEVENS IN CUSTOMERS                    faxnumber = string
        $stmt = $Connection->prepare(
            "INSERT INTO customers (CustomerName, BillToCustomerID, CustomerCategoryID, BuyingGroupID, PrimaryContactPersonID,
                                AlternateContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID, CreditLimit,
                                AccountOpenedDate, StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays,
                                PhoneNumber, FaxNumber, DeliveryRun, RunPosition, WebsiteURL, DeliveryAddressLine1,
                                DeliveryAddressLine2, DeliveryPostalCode, DeliveryLocation, PostalAddressLine1, PostalAddressLine2,
                                PostalPostalCode, LastEditedBy, ValidFrom, ValidTo)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiiiiiiidsdiiissssssssssssiss", $FullName, $BillToCustomerId, $CustomerCategoryId, $BuyingGroupId, $PrimaryContactPersonId,
            $AlternateContactPersonId, $three, $DeliveryCityId, $DeliveryCityId, $CreditLimit, $CurrentDate, $StandardDiscountPercentage, $zero, $zero, $seven,
            $PhoneNumber, $zero, $NULL, $NULL, $zero, $unknown, $address,
            $postcode, $unknown, $unknown, $DeliveryCityName, $postcode, $LastEditedBy, $CurrentDate,
            $ValidTo);
        $execval = $stmt->execute();
        echo $execval;
        //echo "Customer gegevens zijn succesvol toegevoegd aan database!";
        $stmt->close();
    }

    // VRAAG VALUE VAN CUSTOMERID UIT CUSTOMERS EN GEEF DEZE EIGEN VARIABELEN -zodat je ze in people tabel kan inserten!
    $sql = "SELECT CustomerID FROM customers WHERE CustomerName = ('$FullName') AND DeliveryPostalCode =
            ('$postcode') AND DeliveryAddressLine2 = ('$address')";
    $result = $Connection->query($sql);
    $row = mysqli_fetch_array($result);
    $CustomerNUM = reset($row);

    //      Als verbinding gesloten is, wordt de SQL query voorbereid.
    if(isset($_POST["account_aanmaken"]) AND isset($_POST["password"]) AND isset($_POST["confirmpassword"])) {
        if(($_POST['account_aanmaken'] == 'ja') AND (($_POST["password"]) != ($_POST["confirmpassword"]))) {
            // GEGEVENS IN PEOPLE image(Photo) = blob
            $stmt1 = $Connection->prepare("insert into people(FullName, PreferredName, SearchName, IsPermittedToLogon, LogonName,
                            IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson,
                            UserPreferences, PhoneNumber, FaxNumber, EmailAddress, Photo, CustomFields,
                            OtherLanguages, LastEditedBy, ValidFrom, ValidTo, CustomerNUM)
                            values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param("sssisisiiissssbssisss", $FullName, $FirstName, $FullName, $IsPermittedToLogon, $email,
                $IsExternalLogonProvider, $password, $IsSystemUser, $IsEmployee, $IsSalesPerson, $empty,
                $PhoneNumber, $empty, $email, $empty, $empty, $empty, $LastEditedBy, $CurrentDate, $ValidTo, $CustomerNUM);
            $execval = $stmt1->execute();
            echo $execval;
            //echo "People gegevens zijn succesvol toegevoegd aan database!";
            $stmt1->close();
        } else {
            // De wachtwoorden moeten overeenkomen! Maak hier code zodat gebruiker terug wordt verwezen en value gevult wordt
        }
    }
    // UPDATEN BILLTOCUSTOMERID IN TABLE customers
    $query = "UPDATE customers SET BillToCustomerID = ? WHERE CustomerID = ?";
    $Statement = mysqli_prepare($Connection, $query);
    mysqli_stmt_bind_param($Statement, "ii", $CustomerNUM, $CustomerNUM);
    mysqli_stmt_execute($Statement);
}


?>
<form method="post" action="afrekenen.php">
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
                            <td><input type="text" id="voornaam" name="FirstName" placeholder="Voornaam"
                                       value="<?php echo($FirstName); ?>" required></td>
                            <td><input type="text" id="achternaam" name="LastName" placeholder="Achternaam"
                                       value="<?php echo($LastName); ?>" required></td>
                        </tr>
                        <tr>
                            <th>Postcode</th>
                            <th>Huisnummer</th>
                        </tr>
                        <tr>
                            <td><input type="text" id="postcode" name="postcode" placeholder="1234AA"
                                       value="<?php echo($postcode); ?>" required></td>
                            <td><input type="text" id="huisnummer" name="HouseNumber" placeholder="Nr."
                                       value="<?php echo($HouseNumber); ?>" required></td>
                            <td><input type="text" id="toev" name="toev" placeholder="Toev."
                                       value="<?php echo($toev); ?>"></td>
                        </tr>
                        <tr>
                            <th>Straatnaam</th>
                            <th>Woonplaats</th>
                        </tr>

                        <tr>
                            <td><input type="text" id="straatnaam" name="StreetName" placeholder='Straat'
                                       value="<?php echo($StreetName); ?> " required></td>
                            <td><input type="text" id="woonplaats" name="Plaats" placeholder="Plaats"
                                       value="<?php echo($Plaats); ?>" required></td>
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
                            <td><input type="text" id="telefoonnummer" name="PhoneNumber" placeholder="06 12345678"
                                       value="<?php echo($PhoneNumber); ?>" required></td>
                        </tr>
                        <?php if(!isset($_SESSION["login"])) {
                            echo "<div>";
                                echo "<tr>";
                                    echo "<th></th>";
                                    echo "<td><input type='checkbox' name='account_aanmaken' value='ja'></td>";
                                    echo "<td>account aanmaken</td>";
                                echo "</tr>";
                                echo "<tr>";
                                    echo "<th></th>";
                                    echo "<td><input type='password' placeholder='Wachtwoord' name='password' autocomplete='new-password'></td>";
                                    echo "<td><input type='password' placeholder='Bevestig wachtwoord' name='confirmpassword' autocomplete='new-password'></td>";
                                echo "</tr>";
                            echo "</div>";
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
                        <td class="td-geld table-rechts">
                            <?php if ($allTotal < 25) {
                                echo "€" . ($allTotal + 6.25);
                            } else {
                                echo "€" . $allTotal . ",-";
                            } ?>
                        </td>
                    </tr>
                </table>
                <p>Inclusief btw</p>

                <input type="submit" name="Afreken_submit" value="Afrekenen">
            </div>
        </div>
    </div>
</form>
