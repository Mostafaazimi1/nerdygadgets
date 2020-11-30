<?php
if(isset($_POST["sendmail"])) {
   $naam = $_POST["naam"];
   $email = $_POST["email"];
   $phoneNumber = $_POST["phoneNumber"];
   $onderwerp = $_POST["onderwerp"];
   $tekst = $_POST["tekst"];
   $to = "klantenservice.nerdygadgets@gmail.com";

   if ($phoneNumber) {
       $volledigetekst = $tekst . "\nVan: " . $naam . "\nemail: $email" . "\ntelefoonnummer: " . $phoneNumber;
       $mailen = mail($to, $onderwerp, $volledigetekst);
       header("Location: klantenservice.php?bericht&verzenden=succes");
   } else {
       $volledigetekst = $tekst . "\nVan " . $naam . "\n$email";
       $mailen = mail($to, $onderwerp, $volledigetekst);
       header("Location: klantenservice.php?bericht&verzenden=succes");
   }
}