<?php
if(isset($_POST["sendmail"])) {
   $naam = $_POST["naam"];
   $email = $_POST["email"];
   $telNummer = $_POST["telNummer"];
   $onderwerp = $_POST["onderwerp"];
   $tekst = $_POST["tekst"];
   $to = "klantenservice.nerdygadgets@gmail.com";

   if ($telNummer) {
       $volledigetekst = $tekst . "\nVan " . $naam . "\n$email" . "\ntelefoonnummer: " . $telNummer;
       $mailen = mail($to, $onderwerp, $volledigetekst);
       header("Location: klantenservice.php?bericht&verzenden=succes");
   } else {
       $volledigetekst = $tekst . "\nVan " . $naam . "\n$email";
       $mailen = mail($to, $onderwerp, $volledigetekst);
       header("Location: klantenservice.php?bericht&verzenden=succes");
   }
}