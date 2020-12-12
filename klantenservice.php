<?php
include __DIR__ . "/header.php";

if (isset($_SESSION["login"])) {
// Gebruiker is ingelogd
    $gegevens = $_SESSION['login'];
    $personID = $gegevens['PersonID'];
} else{
    $personID= "";
}

if (isset($_POST["submit"]) && isset($_SESSION["login"])) {
    $onderwerp = $_POST["onderwerp"];
    $tekst = $_POST["tekst"];
    $personID = $_POST["submit"];

    //code ticket system
    $sql = "INSERT INTO tickets (personID, title, message) VALUES (?,?,?);";
    $statement = mysqli_prepare($Connection, $sql);
    mysqli_stmt_bind_param($statement, 'iss',$personID,$onderwerp,$tekst);
    mysqli_stmt_execute($statement);


    if (mysqli_stmt_affected_rows($statement) == 1) {
        print("Uw bericht is verzonden!<br><a href='tickets.php'><i class='fas fa-ticket-alt'></i>Tickets</a>");
    } else {
        print("Uw bericht kon niet worden verzonden");
    }
    mysqli_stmt_close($statement);
    mysqli_close($Connection);

} elseif (isset($_POST["submit"]) && !isset($_SESSION["login"])) {
    echo "U bent niet ingelogd";
}
?>
    <h2 class="tekst">Tickets</h2>
    <p>Creëer uw ticket hieronder</p>
    <div class="naastElkaar contactinfo">
        <div class="contactform">
            <form action="klantenservice.php" method="post">
                <div class="form-group">
                    <label>Onderwerp</label>
                    <input type="text" name="onderwerp" placeholder="algemene informatie" required>
                </div>
                <div class="form-group">
                    <label>Uw bericht</label>
                    <textarea class="form-control" rows="3" type="text" name="tekst" placeholder="Stel hier uw vraag of klacht" required></textarea>
                </div>
                <button class="btn btn-primary" type="submit" name="submit" value="<?php echo ($personID) ?>">Verzend</button>
            </form>
        </div>
        <div class="contactinformation">
            <h2 class="tekst">Contact</h2>
            Bij vragen en opmerkingen kunt u gerust met ons contact opnemen.<br>
            Tel: <a href="tel:0612345678">0612345678</a><br>
            E-mail: <a href="mailto:klantenservice.nerdygadgets@gmail.com">klantenservice.nerdygadgets@gmail.com</a><br>
            U kunt ons telefonisch dagelijks bereiken van 8:00 - 18:00.<br><br>
        </div>
    </div><br>

<?php
include __DIR__ . "/footer.php";
?>