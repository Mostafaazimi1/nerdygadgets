<?php
include __DIR__ . "/header.php";

if(isset($_SESSION["login"])) {
    print("<h1>U bent al ingelogd!</h1><br>");
    print('<a href="./">Ga terug naar de homepagina..</a>');
} else {
    if(isset($_POST["email"]) && isset($_POST["password"])) {
        //wanneer email en ww gevult zijn wordt dit uitgevoerd
        $sql = "SELECT FullName, PreferredName, IsPermittedToLogon, LogonName, HashedPassword, PhoneNumber, EmailAddress, CustomerNUM FROM people";
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
                    } else {
                        // De gegevens komen overeen, de gebruiker mag en wordt ingelogd
                        // Array met de gegevens van de klant, geplaatst in $_SESSION['login'] = $loginData;
                        $passFound = TRUE;
                        $loginData = array(
                            "FullName" => $row["FullName"],
                            "PreferredName" => $row["PreferredName"],
                            "IsPermittedToLogon" => $row["IsPermittedToLogon"],
                            "LogonName" => $row["LogonName"],
                            "PhoneNumber" => $row["PhoneNumber"],
                            "EmailAddress" => $row["EmailAddress"],
                        );
                        $_SESSION['login'] = $loginData;
                        $_SESSION['messageCount'] = 1;
                        continue;
                    }
                }
            }
            if(isset($passFound)){
                // Wachtwoord is gevonden, gebruiker wordt geredirect naar home
                if($passFound) {
                    print('<meta http-equiv = "refresh" content = "0; url = ./" />');
                    exit();
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
        <form action="registratie2.0.php" method="post" enctype="multipart/form-data">
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
            <input type="text" placeholder="Postcode" name="PostalCode" required><br>
            <label for="text">
                <i class="fas fa-map-marker-alt"></i>
            </label>
            <input type="text" placeholder="Plaats" name="City" required><br>
            <label for="text">
                <i class="fas fa-road"></i>
            </label>
            <input type="text" placeholder="Straatnaam" name="StreetName" class="loginAddress" */ required>
            <input type="text" placeholder="Huisnummer" name="HouseNumber" class="loginAddress" required><br>
            <input type="submit" value="Registreer!" name="submit" class="btn brn-block btn-primary">
        </form>
    </div>
</div>
<?php }?>