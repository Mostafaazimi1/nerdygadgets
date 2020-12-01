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
<h2 class="tekst">Contact</h2><br>
<div class="naastElkaar contactinfo">
    <div class="contactform">
        <form action="mail.php" method="post">
            <div class="form-group">
                <label>Naam</label>
                <input class="form-control" type="text" name="naam" value="<?php echo ($FullName);?>" placeholder="Voor en/of achternaam" required>
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input class="form-control" type="email" name="email" value="<?php echo ($email);?>" placeholder="voorbeeld@voorbeeld.nl" required>
            </div>
            <div class="form-group">
                <label>Telefoonnummer</label>
                <input class="form-control" type="tel" name="phoneNumber" value="<?php echo ($PhoneNumber);?>" placeholder="06 12345678">
            </div>
            <div class="form-group">
                <label>Onderwerp</label>
                <input class="form-control" type="text" name="onderwerp" placeholder="Waar gaat uw vraag over?" required>
            </div>
            <div class="form-group">
                <label>Uw bericht</label>
                <textarea class="form-control" rows="3" type="text" name="tekst" placeholder="Stel hier uw vraag of klacht" required></textarea>
            </div>
            <button class="btn btn-primary" type="submit" name="sendmail">Verzend</button>
        </form>
    </div>

    <div class="contactinformation">
        Bij vragen en opmerkingen kunt u gerust met ons contact opnemen.<br>
        Tel: <a href="tel:0612345678">0612345678</a><br>
        E-mail: <a href="mailto:klantenservice.nerdygadgets@gmail.com">klantenservice.nerdygadgets@gmail.com</a><br>
        U kunt ons dagelijks bereiken van 8:00 - 18:00.<br><br>
    </div>
</div>









