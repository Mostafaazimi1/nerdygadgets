<?php
include __DIR__ . "/header.php";

if(isset($_SESSION["login"])) {
    print("<h1>U bent al ingelogd!</h1><br>");
    print('<a href="./">Ga terug naar de homepagina..</a>');
} else {
    if(isset($_POST["email"]) && isset($_POST["password"])) {
        //maak functie die controleerd of de gegevens in de database staan. als deze
        // erin staan, ga naar if(TRUE). wanneer het onjuist is, ga naar if(FALSE) op dit moment
        // staat de code er zo in dat het altijd werkt wanneer je iets in email en wachtwoord zet
        // je wordt met alle soort combinaties ingelogt

//        $email = $_POST["email"];
        //hier moet het ww nog gehashed worden
//        $password = $_POST["password"];
//        print($email." ".$password);
//        print("<br>a<br>");

        $sql = "SELECT LogonName,HashedPassword FROM people";
        $result = $Connection->query($sql);
//        print_r($result->fetch_assoc());print("<br>");
//        print_r($result->fetch_assoc());print("<br>");
        if ($result->num_rows > 0) {
            // output data of each row
            $passFound = FALSE;
            $i001 = 0;
            while($row = $result->fetch_assoc()) {
                if(($row["LogonName"] != "NO LOGON") && ($row["LogonName"] == strtolower($_POST["email"])) && ($_POST["password"] == $row["HashedPassword"])) {
                    $passFound = TRUE;
                    continue;
                }
            }
            if($passFound) {
                $_SESSION['login'] = TRUE;
                $_SESSION['messageCount'] = 1;
                print('<meta http-equiv = "refresh" content = "0; url = ./" />');
                exit();
            } else {
                print('<div class="notificationError">');
                print('<h2>We wijzen je graag op het volgende:</h2><br>');
                print('<p>De combinatie van e-mailadres en wachtwoord is niet geldig.</p>');
                print('</div>');
                $email = 'value="' . $_POST["email"] . '"';
                $password = 'value="' . $_POST["password"] . '"';
                //registratiemelding geven
            }
        } else {
            print('<div class="notificationError">');
            print('<h2>We wijzen je graag op het volgende:</h2><br>');
            print('<p>De combinatie van e-mailadres en wachtwoord is niet geldig.</p>');
            print('</div>');
            $email = 'value="' . $_POST["email"] . '"';
            $password = 'value="' . $_POST["password"] . '"';
            //registratie melding geven
        }
    } else {
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