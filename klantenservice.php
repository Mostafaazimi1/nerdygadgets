<?php
include __DIR__ . "/header.php";

if (isset($_SESSION["login"])) {
// Gebruiker is ingelogd
    $gegevens = $_SESSION['login'];
    $FullName = $gegevens['FullName'];
    $email = $gegevens['EmailAddress'];
    $PhoneNumber = $gegevens['PhoneNumber'];
} else{
    $FullName = "";
    $email = "";
    $PhoneNumber = "";
}
?>
<h2>Contact</h2><br>
<div class="naastElkaar contactinfo">
<div class="contactform">
    <form action="mail.php" method="post">
        Naam: <input type="text" name="naam" value="<?php echo ($FullName);?>" placeholder="Voor en/of achternaam" required><br>
        E-mail: <input type="email" name="email" value="<?php echo ($email);?>" placeholder="voorbeeld@voorbeeld.nl" required><br>
        Telefoonnummer: <input type="tel" name="phoneNumber" value="<?php echo ($PhoneNumber);?>" placeholder="06 12345678"><br>
        Onderwerp: <input type="text" name="onderwerp" placeholder="Waar gaat uw vraag over?" required><br>
        Uw bericht: <br><textarea type="text" name="tekst" placeholder="Stel hier uw vraag of klacht" required></textarea><br>
        <button type="submit" name="sendmail">Verzend</button>
    </form>
</div>

<div class="contactinformation">
Bij vragen en opmerkingen kunt u gerust met ons contact opnemen.<br>
Tel: <a href="tel:0612345678">0612345678</a><br>
E-mail: <a href="mailto:klantenservice.nerdygadgets@gmail.com">klantenservice.nerdygadgets@gmail.com</a><br>
U kunt ons dagelijks bereiken van 8:00 - 20:00.<br><br>
</div>


</div>
