<?php
include __DIR__ . "/header.php";

if(isset($_SESSION["login"])) {
    print("<h1>U bent al ingelogd!</h1><br>");
    print('<a href="./">Ga terug naar de homepagina..</a>');
} else {
    if(isset($_POST["email"]) && isset($_POST["password"])) {
        //wanneer email en ww gevult zijn wordt dit uitgevoerd
        $sql = "SELECT FullName, PreferredName, IsPermittedToLogon, LogonName, HashedPassword, PhoneNumber, EmailAddress FROM people";
        $result = $Connection->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            $passFound = FALSE;
            while($row = $result->fetch_assoc()) {
                if(($row["LogonName"] != "NO LOGON") && ($row["LogonName"] == strtolower($_POST["email"])) && ($_POST["password"] == $row["HashedPassword"])) {
                    if($row["IsPermittedToLogon"] == 0) {
                        // als de gebruiker mag niet mag inloggen, krijgt hij hievan een melding
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
<div class="login">
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
<?php }?>