<?php

if(isset($_POST["sendmail"])) {
   $naam = $_POST["naam"];
   $email = $_POST["email"];
   $onderwerp = $_POST["onderwerp"];
   $tekst = $_POST["tekst"];
   $volledigetekst = $tekst . "\nVan " . $naam . "\n$email";

   $to = "klantenservice.nerdygadgets@gmail.com";
   $mailen = mail($to, $onderwerp, $volledigetekst);

   if($tekst == "Stel hier uw vraag of klacht") {
       print("Vul uw vraag of klacht in");
   } else {
       if ($mailen == true) {
           header("Location: klantenservice.php?bericht&verzenden=succes");
       } else {
           print("Uw bericht kon niet worden verzonden");
       }
   }
}