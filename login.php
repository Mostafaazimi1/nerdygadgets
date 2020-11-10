<?php
include __DIR__ . "/header.php";

if(isset($_SESSION["login"])) {
    print("<h1>U bent al ingelogd!</h1>");
} else {
    if(isset($_POST["email"])) {
        //maak functie die controleerd of de gegevens in de database staan. als deze
        // erin staan, ga naar if(TRUE). wanneer het onjuist is, ga naar if(FALSE) op dit moment
        // staat de code er zo in dat het altijd werkt wanneer je iets in email en wachtwoord zet
        // je wordt met alle soort combinaties ingelogt
        if(TRUE) {
            session_start();
            $_SESSION['login'] = TRUE;
            $_SESSION['messageCount'] = 1;
            header("Location: ./index.php");
            //header("Location: index.php?message=success");
            exit();
        }
        if(FALSE) {
            print('<div class="notificationError">');
            print('<h2>We wijzen je graag op het volgende:</h2><br>');
            print('<p>De combinatie van e-mailadres en wachtwoord is niet geldig.</p>');
            print('</div>');
            $email = 'value="' . $_POST["email"] . '"';
            $password = 'value="' . $_POST["password"] . '"';
            // einde stuk code
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