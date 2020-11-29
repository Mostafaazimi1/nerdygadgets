<?php
include __DIR__ . "/header.php";

if (isset($_SESSION["login"])) {
// Gebruiker is ingelogd
    $gegevens = $_SESSION['login'];
    $FullName = $gegevens['FullName'];
    $email = $gegevens['EmailAddress'];
    $HouseNumber = $gegevens['PhoneNumber'];
}
?>
<h2>Contact</h2><br>
<p>Bij vragen en opmerkingen kunt u gerust met ons klantenservice contact opnemen.</p>
    U kunt ons dagelijks bereiken van 9:00-17:00.<br>
    Telefoonnummer: <a href="tel:0612345678">0612345678</a><br>
<br>
U kunt ons ook mailen als bellen voor u niet uitkomt.<br>
Mail: <a href="mailto:klantenservice.nerdygadgets@gmail.com">klantenservice.nerdygadgets@gmail.com</a><br>
<br>
<form action="mail.php" method="post">
    Naam: <input type="text" name="naam" value="<?php echo ($FullName);?>" placeholder="Voor en/of achternaam" required><br>
    Email: <input type="email" name="email" value="<?php echo ($email);?>" placeholder="voorbeeld@voorbeeld.nl" required><br>
    Telefoonnummer: <input type="tel" name="telNummer" value="<?php echo ($HouseNumber);?>" placeholder="06 12345678"><br>
    Onderwerp: <input type="text" name="onderwerp" placeholder="Waar gaat uw vraag over?" required><br>
    Vraag: <br><textarea type="text" name="tekst" placeholder="Stel hier uw vraag of klacht" required></textarea><br>
    <button type="submit" name="sendmail">Verzend</button>
</form>
