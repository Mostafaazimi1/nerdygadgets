<?php
include __DIR__ . "/header.php";

$winkelwagen = $_SESSION['winkelwagen'];
$products = loadProducts($winkelwagen, $Connection);
$complete=false;
$createAccount=false;
$createGuest=false;
$wrongPass=false;


//Start variable
if (isset($_POST['Afreken_submit'])) {
    $FirstName=$_POST['FirstName'];
    $LastName=$_POST['LastName'];
    $postcode=$_POST['postcode'];
    $HouseNumber=$_POST['HouseNumber'];
    $StreetName=$_POST['StreetName'];
    $Plaats=$_POST['Plaats'];
    $email=$_POST['email'];
    $PhoneNumber=$_POST['PhoneNumber'];
} else {
    $FirstName = "";
    $LastName = "";
    $postcode = "";
    $HouseNumber = "";
    $StreetName ="";
    $Plaats = "";
    $email = "";
    $PhoneNumber = "";
}

//Set variable in FORM when logon is set
if (isset($_SESSION["login"])) {
    // Gebruiker is ingelogd
    $gegevens = $_SESSION['login'];
    $HuisnummerStraat = (explode(" ", $gegevens['DeliveryAddressLine2'], 2));
    $FirstName = $gegevens["PreferredName"];
    $LastName = (str_replace($gegevens['PreferredName'] . " ", "", $gegevens['FullName']));
    $postcode = $gegevens['DeliveryPostalCode'];
    $HouseNumber = $HuisnummerStraat[0];
    $StreetName = $HuisnummerStraat[1];
    $Plaats = $gegevens['PostalAddressLine2'];
    $email = $gegevens['EmailAddress'];
    $PhoneNumber = $gegevens['PhoneNumber'];
}
?>

<?php
if (isset($_POST['Afreken_submit'])) {
    $_SESSION['AfrekenGegevens'] = array(
        'voornaam' => $_POST['FirstName'],
        'achternaam' => $_POST['LastName'],
        'postcode' => $_POST['postcode'],
        'huisnummer' => $_POST['HouseNumber'],
        'straat' => $_POST['StreetName'],
        'plaats' => $_POST['Plaats'],
        'email' => $_POST['email'],
        'telefoonnummer' => $_POST['PhoneNumber']
    );
}



if (isset($_SESSION["login"]) AND isset($_POST['Afreken_submit'])) {
    $complete=TRUE;
}

if (!isset($_SESSION["login"]) AND isset($_POST['Afreken_submit'])) {
    if (isset($_POST['account_aanmaken'])) {
        if (($_POST["password"]) == ($_POST["confirmpassword"])) {
            if ((strlen($_POST["password"]) > 7)  AND (preg_match('/[^a-zA-Z]+/', $_POST["password"], $matches)) AND preg_match('/[A-Z]/', $_POST["password"])) {
                $createAccount = TRUE;
                $wrongPass = FALSE;
            } else {
                $addAccount = TRUE;
                print("Het wachtwoord moet minstens 8 karakters bevatten.<br>Daarnaast moet het wachtwoord minimaal 1 speciale teken en een hoofdletter bevatten.");
            }
        } else {
            $wrongPass=TRUE;
//            $FirstName=$_POST['FirstName'];
//            $LastName=$_POST['LastName'];
//            $postcode=$_POST['postcode'];
//            $HouseNumber=$_POST['HouseNumber'];
//            $StreetName=$_POST['StreetName'];
//            $Plaats=$_POST['Plaats'];
//            $email=$_POST['email'];
//            $PhoneNumber=$_POST['PhoneNumber'];
        }
    }
    elseif(!isset($_POST['account_aanmaken'])){
        $createGuest=TRUE;
    }

    if ($createAccount == TRUE or $createGuest == TRUE) {
        $Plaats = ucfirst($_POST["Plaats"]);
        $sql = "
                    SELECT CityName
                    FROM cities
                    WHERE CityName = '" . $Plaats . "'";
        $result = $Connection->query($sql);
        $aantalresult = mysqli_num_rows($result);
        if ($aantalresult < 1) {
            echo("Sorry, in " . $Plaats . " leveren wij niet, voer alsjeblieft een nieuw adres in.");
        } else {
            if ($createAccount == true) {
                $password = $_POST["password"];
                $IsPermittedToLogon = 1;
            } else {
                $password = "NOT SET";
                $IsPermittedToLogon = 0;
                echo("pass NOT SET");

            }
        }
        $DeliveryCityName = $Plaats;
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
        $stmt->close();

        $sql = "SELECT CustomerID FROM customers WHERE CustomerName = ('$FullName') AND DeliveryPostalCode =
                    ('$postcode') AND DeliveryAddressLine2 = ('$address')";
        $result = $Connection->query($sql);
        $row = mysqli_fetch_array($result);
        $CustomerNUM = reset($row);

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
        $stmt1->close();
        $complete=TRUE;
    }
}



//$complete will be set true when $_sesion[login]isset OR Guest / new Customer is created
if($complete){
    echo('<meta http-equiv = "refresh" content = "0; url = ./overzicht.php" />');
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
                            echo "<tr>";
                            echo "<td><input type='checkbox' name='account_aanmaken' value='ja' "; if(isset($addAccount)) {print("checked");} if ($wrongPass){echo("checked");}echo("></td>");
                            echo "<td>account aanmaken</td>";
                            echo "</tr>";
                            echo "<tr>";
                            if ($wrongPass){
                                echo "<tr>";
                                echo "<td>Wachtwoorden komen NIET overeen!</td>";
                                echo "</tr>";
                            }
                            echo "<td><input type='password' placeholder='Wachtwoord' name='password' autocomplete='new-password'></td>";
                            echo "<td><input type='password' placeholder='Bevestig wachtwoord' name='confirmpassword' autocomplete='new-password'></td>";
                            echo "</tr>";
                        }
                        ?>
                    </div>
                </table>

            </div>
            <?php
            $allTotal = 0;
            foreach ($products as $product) {
                $total = (($product['price']*$product['kortingc'] )* $product['aantal']);
                $allTotal += $total;
            }
            ?>
            <div class="prijs-overzicht">
                <h2>Prijs overizcht</h2>
                <table>
                    <tr>
                        <td>Subtotaal</td>
                        <td class="td-geld table-rechts">€<?php echo (round($allTotal,2)); ?></td>
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
                                echo "€" . round($allTotal,2);
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
