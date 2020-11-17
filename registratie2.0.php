<?php
include __DIR__ . "/header.php";
if (isset($_POST["submit"]) AND $_POST["password"] == $_POST["confirmpassword"]) {
    $CustomerName = $_POST["CustomerName"];
    $password = md5($_POST["password"]); //wachtwoord wordt als hash beveiligd
    $email = $_POST["email"];
    $PhoneNumber = $_POST["PhoneNumber"];
    $plaats = $_POST["City"];
    $postcode = $_POST["PostalCode"];
    $adres = $_POST["DeliveryAddressLine2"];
//eerste 4 gegevens in table poeple
    $conn = new mysqli("localhost", "root", "", "nerdygadgets");
    if ($conn->connect_error) {
        echo "$conn->connect_error";
        die("Connection Failed : " . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("insert into klant(LogonName, HashedPassword, EmailAddress, PhoneNumber, Postal) values(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $CustomerName, $password, $email, $PhoneNumber, $postal);
        $execval = $stmt->execute();
        echo $execval;
        echo "Registration successfully...";
        $stmt->close();
        $conn->close();
    }
//laatste 3 gegevens in table customer
    $conn = new mysqli("localhost", "root", "", "nerdygadgets");
    if ($conn->connect_error) {
        echo "$conn->connect_error";
        die("Connection Failed : " . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("insert into customers(PostalAddressLine2, PostalAddressLine1, DeliveryAddressLine2) values(?, ?, ?)");
        $stmt->bind_param("sss", $plaats, $postcode, $adres);
        $execval = $stmt->execute();
        echo $execval;
        echo "Registration successfully...";
        $stmt->close();
        $conn->close();
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
<link rel="stylesheet" href="Style.css" type="text/css">
<div class="login">
        <h1>Maak een account</h1>
        <form action="registratie2.0.php" method="post" enctype="multipart/form-data">
<!--            <div class="alert alert-error"></div>-->
            <label for="text">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" placeholder="Voornaam" name="FirstName" required><br>
            <label for="text">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" placeholder="Achternaam" name="LastName" required><br>
            <label for="email">
                <i class="fas fa-envelope"></i>
            </label>
            <input type="email" placeholder="E-Mail" name="email" required><br>
            <label for="email">
                <i class="fas fa-key"></i>
            </label>
            <input type="password" placeholder="Wachtwoord" name="password" autocomplete="new-password" required><br>
            <label for="email">
                <i class="fas fa-key"></i>
            </label>
            <input type="password" placeholder="Bevestig wachtwoord" name="confirmpassword" autocomplete="new-password" required><br>
            <label for="text">
                <i class="fas fa-phone"></i>
            </label>
            <input type="tel" placeholder="Telefoonnummer" name="PhoneNumber"><br>
            <label for="text">
                <i class="fas fa-mail-bulk"></i>
            </label>
            <input type="text" placeholder="Postcode" name="PostalCode" required><br>
            <label for="text">
                <i class="fas fa-map-marker-alt"></i>
            </label>
            <input type="text" placeholder="Plaats" name="City" required><br>
            <label for="text">
                <i class="fas fa-road"></i>
            </label>
            <input type="text" placeholder="Straatnaam" name="StreetName" class="loginAddress" */ required>
            <input type="text" placeholder="Huisnummer" name="HouseNumber" class="loginAddress" required><br>
            <input type="submit" value="Registreer!" name="submit" class="btn brn-block btn-primary">
        </form>
</div>
</body>
</html>
