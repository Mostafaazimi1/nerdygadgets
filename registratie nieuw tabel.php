<?php
//VRAGEN BIJ REGISTRATIE:
//voornaam
//achternaam
//email
//wachtwoord
//telnummer
//stad
//huisnummer
//straatnaam
//postcode
//MOET TOEGEVOEGD:
//current date		maken met variabele
//customerid		uit tabel customer halen naar people
//particuliersid		customercategoryid moet in customer (komt uit customercategories)
//
//nieuwe customercategoryid aanmaken met nr. 9;
//INSERT INTO customercategories VALUES (9, 'Private', 1, '2020-01-01', '9999-12-31')
//
//deliverycityid		uit tabel customer naar people!
include __DIR__ . "/header.php";
if (isset($_POST["submit"]) AND $_POST["password"] == $_POST["confirmpassword"]) {
    if ($_POST["password"] != $_POST["confirmpassword"]) {
        echo("De wachtwoorden moeten overeenkomen!");
    } else {
        //KOMT ER NOG IN
        $plaats = $_POST["Plaats"];
        //KOMT $plaats VOOR IN COLUMN CITYNAME VAN TABEL CITIES ZO JA RETURN COLUMN VALUE VAN CITYID EN GEEF DEZE AAN $DeliveryCityId
        if ($Connection->connect_error) {
            echo "$Connection->connect_error";
            die("Connection Failed : " . $Connection->connect_error);
        } else {
            $Plaats = ucfirst($plaats);
            $sql = "
                    SELECT CityName
                    FROM cities
                    WHERE CityName = ('$plaats') OR ('$Plaats')
                    LIMIT 1";
            $result = $Connection->query($sql);
            $aantalresult = mysqli_num_rows($result);
            if ($aantalresult > 0) {
                $DeliveryCityId = $Plaats;
                echo $DeliveryCityId;
            }   else {
                echo ("Sorry, in ".$Plaats." leveren wij niet.");
            }
            $Connection->close();
        }
        $password = md5($_POST["password"]); //wachtwoord wordt als hash beveiligd
        $email = $_POST["email"];
        $PhoneNumber = $_POST["PhoneNumber"];
        $postcode = $_POST["PostCode"];
        $FirstName = $_POST["FirstName"];
        $LastName = $_POST["LastName"];
        $HouseNumber = $_POST["HouseNumber"];
        $StreetName = $_POST["StreetName"];
        $CurrentDate = date("Y/m/d");
        $FullName = ($FirstName." ".$LastName);

        //Als verbinding gesloten is, wordt de SQL query voorbereid.
        if ($Connection->connect_error) {
            echo "$Connection->connect_error";
            die("Connection Failed : " . $Connection->connect_error);
        } else {
            // GEGEVENS IN CUSTOMERS                    faxnumber = string
            $stmt = $Connection->prepare(
                "insert into customers(CustomerName, BillToCustomerId, CustomerCategoryId, BuyingGroupId, PrimaryContactPerson,
                                                AlternateContactPerson, DeliveryMethodID, DeliveryCityID, PostalCityID, CreditLimit,
                                                AccountOpenedDate, StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays,
                                                PhoneNumber, FaxNumber, DeliveryRun, RunPosition, WebsiteURL, DeliveryAddressLine1,
                                                DeliveryAddressLine2, DeliveryPostalCode, DeliveryLocation, PostalAddressLine1, PostalAddressLine2,
                                                PostalPostalCode, LastEditedBy, ValidFrom, ValidTo)
                                                values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siiiiiiiidsdiiissssssssssssiss", $FullName, $CustomerId, 9, 0, 0,
                0, 3, $CityName, $DeliveryCityId, 0.00, $CurrentDate, 0.000, 0, 0, 7,
                $PhoneNumber, 0, NULL, NULL, 0, "unknown", $HouseNumber." ".$StreetName,
                $postcode, "unknown", "unknown", "unknown", $postcode, 0, $CurrentDate,
                "9999-12-31");
            $execval = $stmt->execute();
            echo $execval;
            echo "Customer gegevens zijn succesvol toegevoegd aan database!";
            $stmt->close();
            $Connection->close();
        }
    }

    // VRAAG VALUE VAN CUSTOMERID UIT CUSTOMERS EN GEEF DEZE EIGEN VARIABELEN -zodat je ze in people tabel kan inserten!


//      Als verbinding gesloten is, wordt de SQL query voorbereid.
        if ($Connection->connect_error) {
            echo "$Connection->connect_error";
            die("Connection Failed : " . $Connection->connect_error);
        } else {
            // GEGEVENS IN PEOPLE                 image(Photo) = blob
            $stmt = $Connection->prepare(
                    "insert into people(FullName, PreferredName, SearchName, IsPermittedToLogon, LogonName
                                            IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson
                                            UserPreferences, PhoneNumber, FaxNumber, EmailAddress, Photo, CustomFields
                                            OtherLanguages, LastEditedBy, ValidFrom, ValidTo, CustomerNUM)
                                            values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisibiiissssbssisss", $FullName, $Firstname, $FullName, 1, $email,
                                                0, $password, 0, 0, 0, "", $PhoneNumber, "", $email, "", "", "", 1, $CurrentDate, "9999-12-31", $CustomerId);
            $execval = $stmt->execute();
            echo $execval;
            echo "People gegevens zijn succesvol toegevoegd aan database!";
            $stmt->close();
            $Connection->close();
        }
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
<link rel="stylesheet" type="text/css">
<div class="body-content">
    <div class="module">
        <h1>Maak een account</h1>
        <form action="registratie2.0.php" method="post" enctype="multipart/form-data">
            <div class="alert alert-error"></div>
            <input type="text" placeholder="Voornaam" name="FirstName" required><br>
            <input type="text" placeholder="Achternaam" name="LastName" required><br>
            <input type="email" placeholder="E-Mail" name="email" required><br>
            <input type="password" placeholder="Wachtwoord" name="password" autocomplete="new-password" required><br>
            <input type="password" placeholder="Bevestig wachtwoord" name="confirmpassword" autocomplete="new-password"
                   required><br>
            <input type="tel" placeholder="Telefoonnummer" name="PhoneNumber"><br>
            <input type="text" placeholder="Plaats" name="Plaats" required><br>
            <input type="text" placeholder="Straatnaam" name="StreetName" required>
            <input type="text" placeholder="Huisnummer" name="HouseNumber" required><br>
            <input type="text" placeholder="Postcode" name="PostCode" required><br>
            <input type="submit" value="Registreer!" name="submit" class="btn brn-block btn-primary">
        </form>
    </div>
</div>
</body>
</html>
