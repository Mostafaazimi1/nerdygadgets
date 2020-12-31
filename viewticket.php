<?php
include __DIR__ . "/header.php";

if (isset($_GET['ticket'])) {
    $id = $_GET['ticket'];

    $Query = " SELECT t.id, p.preferredName, t.title, t.message, t.created, t.status
      FROM tickets t JOIN people p ON t.personID = p.personID
      WHERE id = '$id'";
    $result = mysqli_query($Connection, $Query);

    $sql = " SELECT p.preferredName, p.isSalesperson, m.reactMessage, m.reactDate 
            FROM message m JOIN people p ON p.personID = m.personID WHERE m.ticketID = '$id'";
    $result2 = mysqli_query($Connection, $sql);

    if (isset($_POST['submit'])) {
        $message = $_POST['reactie'];
        $personID = $_POST['submit'];
        $ticketID = $_GET['ticket'];

        $Query = " INSERT INTO message (ticketID, personID, reactMessage) VALUES (?,?,?)";
        $statement = mysqli_prepare($Connection, $Query);
        mysqli_stmt_bind_param($statement, 'iis',$ticketID,$personID,$message);
        mysqli_stmt_execute($statement);
        if (mysqli_stmt_affected_rows($statement) == 1) {
            print("Uw bericht is verzonden!<br>");
        } else {
            print("Uw bericht kon niet worden verzonden<br>");
        }
        mysqli_stmt_close($statement);
        mysqli_close($Connection);
}
?>
<a href="tickets.php" class="btn btn-primary">Ga terug</a>
<H2 class="tekst">Comment</H2>
<div class="naastElkaar contactinfo">
    <div class="eenticket">
<?php
while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $nickName = $row['preferredName'];
    $title = $row['title'];
    $message = $row['message'];
    $created = $row['created'];
    $status = $row['status'];

    print ($title . " " . $nickName . " " . $created . " status: " . $status . "<br>" . $message);
}?>
<h2 class="tekst">Reacties</h2>
<table>
<tbody>
<?php
    while ($row = mysqli_fetch_assoc($result2)) {
        $nickName = $row['preferredName'];
        $medewerker = $row['isSalesperson'];
        $reactMessage = $row['reactMessage'];
        $reactDate = $row['reactDate'];
?>
<tr>
    <td><?php if ($medewerker) { print('Medewerker ');} print ($nickName . "  " . $reactDate . "<br>" . $reactMessage); ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<?php
if (isset($_SESSION["login"])) {
$gegevens = $_SESSION['login'];
$personID = $gegevens['PersonID'];
?>
<form action="viewticket.php?ticket=<?php echo $id; ?>" method="post">
    <div class="form-group">
        <label>Reactie</label>
        <textarea class="form-control" rows="3" type="text" name="reactie" required></textarea>
    </div>
    <button class="btn btn-primary" type="submit" name="submit" value="<?php echo ($personID) ?>">Verzend</button>
</form>
    </div>
    <div class="ticketinfo2">
        <a href="klantenservice.php">Klik hier</a> als u een ticket wilt creëeren
    </div>
<?php
} else {
print("<br><a href='login.php'>Klik hier</a> om te kunnen aanmelden<br>");
?>
    </div>
    <div class="ticketinfo2">
        <a href="klantenservice.php">Klik hier</a> als u een ticket wilt creëeren (Let op! U moet ingelogd zijn om dit te kunnen doen.)
    </div>
<?php
}?>
</div><br>
<?php
}
include __DIR__ . "/footer.php";
?>