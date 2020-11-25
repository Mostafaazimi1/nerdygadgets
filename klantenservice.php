<?php
include __DIR__ . "/header.php";

?>
<form action="mail.php" method="post">
    Naam: <input type="text" name="naam"><br>
    Email: <input type="email" name="email"><br>
    Onderwerp: <input type="text" name="onderwerp"><br>
    Vraag: <br>
    <textarea type="text" name="tekst">Stel hier uw vraag of klacht</textarea><br>
    <button type="submit" name="sendmail">Verzend</button>
</form>
