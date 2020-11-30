<?php
include __DIR__ . "/header.php";

if(isset($_SESSION["login"])) {
    print("<h1>U bent al ingelogd!</h1><br>");
    print('<a href="./">Ga terug naar de homepagina..</a>');
} else {
    if(isset($_GET['orderID'])) {
        $_SESSION['reviewID'] = $_GET['orderID'];
        print('<div>');
        print('<a class="buttonClass" href="./view.php?id=' . $_SESSION['reviewID'] . '">Terug</a>');
        print('&#160;&#160;<a class="buttonClass" href="./">Home pagina</a>');
        print('</div><br>');
    }
    if(isset($_POST["email"]) && isset($_POST["password"])) {
        //wanneer email en ww gevult zijn wordt dit uitgevoerd
        $sql = "SELECT PersonID, FullName, PreferredName, IsPermittedToLogon, LogonName, HashedPassword, EmailAddress, CustomerNUM FROM people";
        $result = $Connection->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $passFound = FALSE;
            while($row = $result->fetch_assoc()) {
                if(($row["LogonName"] != "NO LOGON") && ($row["LogonName"] == strtolower($_POST["email"])) && ($_POST["password"] == $row["HashedPassword"])) {
                    if($row["CustomerNUM"] == "") {
                        // Er is geen customerid gekoppeld, dus de opgeslagen account gegevens zijn incompleet.
                        // De gebruiker krijgt hievan een melding
                        unset($passFound);
                        $email = 'value="' . $_POST["email"] . '"';
                        $password = 'value="' . $_POST["password"] . '"';
                        print('<div class="notificationError">');
                        print('<h2>We wijzen je graag op het volgende:</h2><br>');
                        print('<p>Uw accountgegevens zijn beschadigd. Neem alstublieft contact op met de systeembeheerder.</p>');
                        print('</div>');
                    } elseif(($row["IsPermittedToLogon"] == 0)) {
                        // De gebruiker mag niet mag inloggen, hij krijgt hievan een melding
                        unset($passFound);
                        $email = 'value="' . $_POST["email"] . '"';
                        $password = 'value="' . $_POST["password"] . '"';
                        print('<div class="notificationError">');
                        print('<h2>We wijzen je graag op het volgende:</h2><br>');
                        print('<p>Uw account is uitgeschakeld door de systeembeheerder.</p>');
                        print('</div>');
                        //registratiemelding geven
                    } elseif ($row["CustomerNUM"] != "") {
                        // De gegevens komen overeen, de gebruiker mag en wordt ingelogd
                        // Array met de gegevens van de klant, geplaatst in $_SESSION['login'] = $loginData;
                        $_SESSION['messageCount'] = 1;
                        $passFound = TRUE;
                        $loginData = array(
                            "PersonID" => $row["PersonID"],
                            "FullName" => $row["FullName"],
                            "PreferredName" => $row["PreferredName"],
                            "IsPermittedToLogon" => $row["IsPermittedToLogon"],
                            "LogonName" => $row["LogonName"],
                            "EmailAddress" => $row["EmailAddress"],
                        );
                        $sql2 = "SELECT CustomerID, CustomerName, DeliveryMethodID, DeliveryCityID,
                                    PostalCityID, PhoneNumber, DeliveryAddressLine2, DeliveryPostalCode,
                                    PostalPostalCode, PostalAddressLine2 FROM Customers WHERE CustomerID = ".$row["CustomerNUM"].";";
                        $result2 = $Connection->query($sql2);
                        if ($result2->num_rows > 0) {
                            while ($row2 = $result2->fetch_assoc()) {
                                $sql3 = "SELECT CityName FROM cities WHERE CityID = '".$row2["DeliveryCityID"]."' LIMIT 1";
                                $result3 = $Connection->query($sql3);
                                if ($result3->num_rows > 0) {
                                    while ($row3 = $result3->fetch_assoc()) {
                                        $loginData["CityName"] = $row3["CityName"];
                                        continue;
                                    }
                                }
                                // Voeg bijvehorende gegevens van de klant toe aan de array uit customers tabel
                                $loginData["CustomerID"] = $row2["CustomerID"];
                                $loginData["CustomerName"] = $row2["CustomerName"];
                                $loginData["DeliveryMethodID"] = $row2["DeliveryMethodID"];
                                $loginData["DeliveryCityID"] = $row2["DeliveryCityID"];
                                $loginData["PostalCityID"] = $row2["PostalCityID"];
                                $loginData["PhoneNumber"] = $row2["PhoneNumber"];
                                $loginData["DeliveryAddressLine2"] = $row2["DeliveryAddressLine2"];
                                $loginData["DeliveryPostalCode"] = $row2["DeliveryPostalCode"];
                                $loginData["PostalPostalCode"] = $row2["PostalPostalCode"];
                                $loginData["PostalAddressLine2"] = $row2["PostalAddressLine2"];
                                continue;
                            }
                        }
                        $_SESSION['login'] = $loginData;
                        continue;
                    } else {
                        print("error! No connection with customer table");
                    }
                }
            }
            if(isset($passFound)){
                // Wachtwoord is gevonden, gebruiker wordt geredirect naar home
                if($passFound) {
                    if(isset($_SESSION['reviewID'])) {
                        $reviewID = $_SESSION['reviewID'];
                        unset($_SESSION['reviewID']);
                        print('<meta http-equiv = "refresh" content = "0; url = ./review.php?orderID='.$reviewID.'&reviewButton=Schrijf+een+review&newLogin=1" />');
                        exit();
                    } else {
                        print("nee");
//                        print('<meta http-equiv = "refresh" content = "0; url = ./" />');
                        exit();
                    }
                } else {
                    // Wachtwoord is niet gevonden, gebruiker moet opnieuw invoeren
                    print('<div class="notificationError">');
                    print('<h2>We wijzen je graag op het volgende:</h2><br>');
                    print('<p>De combinatie van e-mailadres en wachtwoord is niet geldig.</p>');
                    print('</div>');
                    $email = 'value="' . $_POST["email"] . '"';
                    $password = 'value="' . $_POST["password"] . '"';
                    //registratiemelding geven
                }
            }
        } else {
            // De database is leeg, dus er staan geen accounts in
            print('<div class="notificationError">');
            print('<h2>We wijzen je graag op het volgende:</h2><br>');
            print('<p>De combinatie van e-mailadres en wachtwoord is niet geldig.</p>');
            print('</div>');
            $email = 'value="' . $_POST["email"] . '"';
            $password = 'value="' . $_POST["password"] . '"';
            //registratie melding geven
        }
    } else {
        // Extra zekering, beide velden moeten gevuld zijn. Dit wordt ook op html afgedwongen.
        $email = 'placeholder="E-mailadres*"';
        $password = 'placeholder="Wachtwoord*"';
    }
?>
<div class="row">
    <div class="sublogin">
        <div class="login" >
            <h1>Inloggen</h1>
            <form action="login.php" method="post">
                <label for="email">
                    <i class="fas fa-user"></i>
                </label>
                <input type="email" name="email" <?php print($email);?> id="email" required>
                <label for="password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" name="password" <?php print($password);?> required>
                <input type="submit" name="submitLogin" class="button" value="Inloggen">
            </form>
        </div>
    </div>
    <div class="login">
        <h1>Maak een account</h1>
        <form action="registratie.php" method="post" enctype="multipart/form-data">
            <!--            <div class="alert alert-error"></div>-->
            <label for="text">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" placeholder="Voornaam" name="FirstName" required><br>
            <label for="text">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" placeholder="Achternaam" name="LastName" required><br>
            <label for="email">
                <i class="fas fa-envelope"></i>
            </label>
            <input type="email" placeholder="E-Mail" name="email" required><br>
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
            <input type="tel" placeholder="Telefoonnummer" name="PhoneNumber"><br>
            <label for="text">
                <i class="fas fa-mail-bulk"></i>
            </label>
            <input type="text" placeholder="Postcode" name="PostCode" required><br>
            <label for="text">
                <i class="fas fa-map-marker-alt"></i>
            </label>
            <input type="text" placeholder="Plaats" name="Plaats" required><br>
            <label for="text">
                <i class="fas fa-road"></i>
            </label>
            <input type="text" placeholder="Straatnaam" name="StreetName" class="loginStraat" */ required>
            <input type="text" placeholder="Huisnummer" name="HouseNumber" class="loginhuisNummer" required><br>
            <input type="submit" value="Registreer!" name="submit" class="btn brn-block btn-primary">
        </form>
    </div>
</div>
<?php }?>