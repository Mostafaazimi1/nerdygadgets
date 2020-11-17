<?php
include __DIR__ . "/header.php";
if (isset($_POST["submit"]) AND $_POST["password"] == $_POST["confirmpassword"]) {
    print("aaa");
    $CustomerName = $_POST["CustomerName"];
    $password = md5($_POST["password"]); //wachtwoord wordt als hash beveiligd
    $email = $_POST["email"];
    $PhoneNumber = $_POST["PhoneNumber"];
    $plaats = $_POST["PostalAddressLine2"];
    $postcode = $_POST["PostalAddressLine1"];
    $adres = $_POST["DeliveryAddressLine2"];

    if ($Connection->connect_error) {
        echo "$Connection->connect_error";
        die("Connection Failed : " . $Connection->connect_error);
    } else {
        $stmt = $Connection->prepare("INSERT INTO klant (LogonName, HashedPassword, EmailAddress, PhoneNumber, PostalAddressLine2, PostalAddressLine1, DeliveryAddressLine2) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $CustomerName, $password, $email, $PhoneNumber, $plaats, $postcode, $adres);
        $execval = $stmt->execute();
        echo $execval;
        echo "Registration successfully...";
        $stmt->close();
        $Connection->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<link href="http://localhost/nerdygadgets/" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css">
<div class="body-content">
    <div class="module">
        <h1>Maak een account</h1>
        <form action="registratie2.0.php" method="post" enctype="multipart/form-data">
            <div class="alert alert-error"></div>
            <input type="text" placeholder="Gebruikersnaam" name="CustomerName" required><br>
            <input type="password" placeholder="Wachtwoord" name="password" autocomplete="new-password" required><br>
            <input type="password" placeholder="Bevestig wachtwoord" name="confirmpassword" autocomplete="new-password"
                   required><br>
            <input type="email" placeholder="E-Mail" name="email" required><br>
            <input type="tel" placeholder="Telefoonnummer" name="PhoneNumber"><br>
            <input type="text" placeholder="Plaats" name="PostalAddressLine2" required><br>
            <input type="text" placeholder="Postcode" name="PostalAddressLine1" required><br>
            <input type="text" placeholder="Adres" name="DeliveryAddressLine2" required><br>
            <input type="submit" value="Registreer!" name="submit" class="btn brn-block btn-primary">
        </form>
    </div>
</div>
</body>
</html>
