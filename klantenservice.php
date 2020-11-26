<?php
include __DIR__ . "/header.php";

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
    Naam: <input type="text" name="naam"><br>
    Email: <input type="email" name="email"><br>
    Onderwerp: <input type="text" name="onderwerp"><br>
    Vraag: <br>
    <textarea type="text" name="tekst">Stel hier uw vraag of klacht</textarea><br>
    <button type="submit" name="sendmail">Verzend</button>
</form>
