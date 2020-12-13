<?php
include __DIR__ . "/header.php";

if (isset($_SESSION["login"])) {
// Gebruiker is ingelogd
    $gegevens = $_SESSION['login'];
    $personID = $gegevens['PersonID'];

if (isset($_GET['ticket'])) {
    $id = $_GET['ticket'];


$Query = " SELECT t.id, p.preferredName, t.title, t.message, t.created, t.status
          FROM tickets t JOIN people p ON t.personID = p.personID
          WHERE id = '$id'";
$result = mysqli_query($Connection, $Query);


if (isset($_POST['submit'])) {
    $message = $_POST['reactie'];
    $personID = $_POST['submit'];
    $ticketID = $_GET['ticket'];

    $Query = " INSERT INTO message (ticketID, personID, reactMessage) VALUES (?,?,?)";
    $statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($statement, 'iis',$ticketID,$personID,$message);
    mysqli_stmt_execute($statement);
    if (mysqli_stmt_affected_rows($statement) == 1) {
        print("Uw bericht is verzonden!");
    } else {
        print("Uw bericht kon niet worden verzonden");
    }
    mysqli_stmt_close($statement);
    mysqli_close($Connection);
}


?>
<a href="servicedesk.php" class="btn btn-primary">Ga terug</a>
<H2>Comment</H2>
    <?php
    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['id'];
        $nickName = $row['preferredName'];
        $title = $row['title'];
        $message = $row['message'];
        $created = $row['created'];
        $status = $row['status'];

        ?>

        <?php print($nickName)?>
        <?php print ($title)?>
		<?php print ($message)?>
		<?php print ($created)?><br><br>

        <?php
    }

    $sql = " SELECT p.preferredName, p.isSalesperson, m.reactMessage, m.reactDate 
                FROM message m JOIN people p ON p.personID = m.personID WHERE m.ticketID = '$id'";
    $result1 = mysqli_query($Connection, $sql);

    ?>
    <h2>reacties</h2>
    <?php

    while ($row = mysqli_fetch_assoc($result1))
    {
        $nickName = $row['preferredName'];
        $medewerker = $row['isSalesperson'];
        $reactMessage = $row['reactMessage'];
        $reactDate = $row['reactDate'];

    ?>
        <span class="con">
                    <?php if ($medewerker) { print('Medewerker ');}?>
				<span class="title"><?php print ($nickName)?></span>
				<span class="msg"><?php print ($reactMessage)?></span>
			</span>
        <span class="con created"><?php print ($reactDate)?></span><br>


        <?php
    }
        ?>

    <form action="viewticket.php?ticket=<?php echo $id; ?>" method="post">
        <div class="form-group">
            <label>Reactie</label>
            <textarea class="form-control" rows="3" type="text" name="reactie" required></textarea>
        </div>
        <button class="btn btn-primary" type="submit" name="submit" value="<?php echo ($personID) ?>">Verzend</button>
    </form>


    <?php
    }
    }else {
    echo "Geen toegang voor niet-ingelogde gebruikers.";
    }
include __DIR__ . "/footer.php";
?>
