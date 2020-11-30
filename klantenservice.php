<?php
include __DIR__ . "/header.php";

if (isset($_SESSION["login"])) {
// Gebruiker is ingelogd
    $gegevens = $_SESSION['login'];
    $FullName = $gegevens['FullName'];
    $email = $gegevens['EmailAddress'];
    $HouseNumber = $gegevens['PhoneNumber'];
} else{
    $FullName = "";
    $email = "";
    $HouseNumber = "";
}
?>
<h2>Contact</h2><br>
<p>Bij vragen en opmerkingen kunt u gerust met ons klantenservice contact opnemen.</p>

U kunt ons dagelijks telefonisch bereiken van 9:00-17:00.<br>
Telefoonnummer: <a href="tel:0612345678">0612345678</a><br>
<br>
U kunt ons altijd mailen. (Onze klantenservice reageert zo snel mogelijk op uw vraag)<br>
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

<!--Niet zeker of dit wordt geimplementeerd...-->
<!--<br><br>-->
<!--<h2>Vragen</h2>-->
<!--<h5>Is het normaal dat ik nog steeds geen reactie van de klantenservice heb gekregen sinds 2 dagen nadat ik het contactformulier/email heb verzonden?</h5>-->
<!--<p>Onze klantenservice probeert altijd zo snel mogelijk uw vraag te beantwoorden. Meestal duurt het maximaal 1 à 2 dagen voordat u reactie krijgt, in uiterste gevalllen wanneer het heel druk is kan het tot 5 dagen duren. Vind u dat het heel dringend is? Dan kunt u proberen telefonisch contact opnemen met de klantenservice</p>-->
