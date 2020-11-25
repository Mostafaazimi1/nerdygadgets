<?php

if(isset($_POST["sendmail"])) {
   $naam = $_POST["naam"];
   $email = $_POST["email"];
   $onderwerp = $_POST["onderwerp"];
   $tekst = $_POST["tekst"];
   $volledigetekst = $tekst . "\nVan " . $naam . "\n$email";

   $to = "klantenservice.nerdygadgets@gmail.com";
   $mailen = mail($to, $onderwerp, $volledigetekst);

    if($mailen == true) {
        header("Location: klantenservice.php?bericht&verzenden=succes");
    } else {
        print("Uw bericht kon niet worden verzonden");
    }
}