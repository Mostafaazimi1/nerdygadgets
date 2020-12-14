<?php
include __DIR__ . "/header.php";
if (isset($_POST["submit"]) AND $_POST["password"] == $_POST["confirmpassword"]) {
    $FirstName = $_POST["FirstName"];
    $LastName = $_POST["LastName"];
    $email = $_POST["email"];
    $PhoneNumber = $_POST["PhoneNumber"];
    $postcode = $_POST["PostCode"];
    $Plaats = ucfirst($_POST["Plaats"]);
    $DeliveryCityName = $Plaats;
    $StreetName = $_POST["StreetName"];
    $HouseNumber = $_POST["HouseNumber"];
    if ($_POST["password"] != $_POST["confirmpassword"]) {
        echo("De wachtwoorden moeten overeenkomen!");


    } elseif ((strlen($_POST["password"]) > 7)  AND (preg_match('/[^a-zA-Z]+/', $_POST["password"], $matches)) AND preg_match('/[A-Z]/', $_POST["password"])) {
        //KOMT $plaats VOOR IN COLUMN CITYNAME VAN TABEL CITIES ZO JA RETURN COLUMN VALUE VAN CITYID EN GEEF DEZE AAN $DeliveryCityId
        // ANDERS AFBREKEN
        if(PostcodeCheck($postcode)) {
            $sql = "
                    SELECT CityName
                    FROM cities
                    WHERE CityName = '" . $Plaats . "'";
            $result = $Connection->query($sql);
            $aantalresult = mysqli_num_rows($result);
            if ($aantalresult < 1) {
                echo("Sorry, in " . $Plaats . " leveren wij niet, voer alsjeblieft een nieuw adres in.");
                // MOET ERBIJ: GEGEVENS OPSLAAN BIJ FOUT
            } else {
                $password = $_POST["password"];
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
                if ($Connection->connect_error) {
                    echo "$Connection->connect_error";
                    die("Connection Failed : " . $Connection->connect_error);
                } else {
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
//            echo "Customer gegevens zijn succesvol toegevoegd aan database!";
                    $stmt->close();
                }
            }

            // VRAAG VALUE VAN CUSTOMERID UIT CUSTOMERS EN GEEF DEZE EIGEN VARIABELEN -zodat je ze in people tabel kan inserten!
            if ($Connection->connect_error) {
                echo "$Connection->connect_error";
                die("Connection Failed : " . $Connection->connect_error);
            } else {
                $sql = "SELECT CustomerID FROM customers
            WHERE CustomerName = ('$FullName') AND DeliveryPostalCode = ('$postcode') AND DeliveryAddressLine2 = ('$address')";
                $result = $Connection->query($sql);
                $row = mysqli_fetch_array($result);
                $CustomerNUM = reset($row);
            }


//      Als verbinding gesloten is, wordt de SQL query voorbereid.
            if ($Connection->connect_error) {
                echo "$Connection->connect_error";
                die("Connection Failed : " . $Connection->connect_error);
            } else {
                // GEGEVENS IN PEOPLE                 image(Photo) = blob
                $stmt = $Connection->prepare(
                    "insert into people(FullName, PreferredName, SearchName, IsPermittedToLogon, LogonName,
                                            IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson,
                                            UserPreferences, PhoneNumber, FaxNumber, EmailAddress, Photo, CustomFields,
                                            OtherLanguages, LastEditedBy, ValidFrom, ValidTo, CustomerNUM)
                                            values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssisisiiissssbssisss", $FullName, $FirstName, $FullName, $IsPermittedToLogon, $email,
                    $IsExternalLogonProvider, $password, $IsSystemUser, $IsEmployee, $IsSalesPerson, $empty,
                    $PhoneNumber, $empty, $email, $empty, $empty, $empty, $LastEditedBy, $CurrentDate, $ValidTo, $CustomerNUM);
                $execval1 = $stmt->execute();
                echo $execval1;
                //echo "People gegevens zijn succesvol toegevoegd aan database!";
                $stmt->close();
                $_SESSION['messageCount3'] = 1;
                print('<meta http-equiv = "refresh" content = "0; url = ./" />');
            }
            // UPDATEN BILLTOCUSTOMERID IN TABLE customers
            if ($Connection->connect_error) {
                echo "$Connection->connect_error";
                die("Connection Failed : " . $Connection->connect_error);
            } else {
                $query = "UPDATE customers SET BillToCustomerID = ? WHERE CustomerID = ?";
                $Statement = mysqli_prepare($Connection, $query);
                mysqli_stmt_bind_param($Statement, "ii", $CustomerNUM, $CustomerNUM);
                mysqli_stmt_execute($Statement);
            }
        } else {
            print("De invoer van de postcode was onjuist, moet zijn als: 1111AA<br>");
        }
    }   else {
        print("Het wachtwoord moet minstens 8 karakters bevatten.<br>Daarnaast moet het wachtwoord minimaal 1 speciale teken en een hoofdletter bevatten.");
    }
} else {
    $FirstName = '';
    $LastName = '';
    $email = '';
    $PhoneNumber = '';
    $postcode = '';
    $DeliveryCityName = '';
    $StreetName = '';
    $HouseNumber = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<link href="http://localhost/nerdygadgets/" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="Style.css" type="text/css">
<div class="login">
    <h1>Maak een account</h1>
    <form action="registratie.php" method="post" enctype="multipart/form-data">
        <!--            <div class="alert alert-error"></div>-->
        <label for="text">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" placeholder="Voornaam" value='<?php print($FirstName);?>' name="FirstName" required><br>
        <label for="text">
            <i class="fas fa-user"></i>
        </label>
        <input type="text" placeholder="Achternaam" value='<?php print($LastName);?>' name="LastName" required><br>
        <label for="email">
            <i class="fas fa-envelope"></i>
        </label>
        <input type="email" placeholder="E-Mail" value='<?php print($email);?>' name="email" required><br>
        <label for="email">
            <i class="fas fa-key"></i>
        </label>
        <input type="password" placeholder="Wachtwoord" name="password" autocomplete="new-password" required><br>
        <label for="email">
            <i class="fas fa-key"></i>
        </label>
        <input type="password" placeholder="Bevestig wachtwoord" name="confirmpassword" autocomplete="new-password" required><br>
        <label for="text">
            <i class="fas fa-phone"></i>
        </label>
        <input type="tel" placeholder="Telefoonnummer" value='<?php print($PhoneNumber);?>' name="PhoneNumber"><br>
        <label for="text">
            <i class="fas fa-mail-bulk"></i>
        </label>
        <input type="text" placeholder="Postcode" value='<?php print($postcode);?>' name="PostCode" required><br>
        <label for="text">
            <i class="fas fa-map-marker-alt"></i>
        </label>
        <input type="text" placeholder="Plaats" value='<?php print($DeliveryCityName);?>' name="Plaats" required><br>
        <label for="text">
            <i class="fas fa-road"></i>
        </label>
        <input type="text" class="loginStraat" placeholder="Straatnaam" value='<?php print($StreetName);?>' name="StreetName" class="loginAddress" */ required>
        <input type="text" class="loginhuisNummer" placeholder="Huisnummer" value='<?php print($HouseNumber);?>' name="HouseNumber" class="loginAddress" required><br>
        <input type="submit" value="Registreer!" name="submit" class="btn brn-block btn-primary">
    </form>
</div>
</body>
</html>

