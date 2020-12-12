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

//$Sql = " SELECT *
//          FROM message
//          WHERE id = '$id'";
//$resultcomments = mysqli_query($Connection, $Sql);

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

<H2>Comment</H2>
<div class="tickets-list">
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
        <span class="con">
				<span class="title"><?php print ($title)?></span>
				<span class="msg"><?php print ($message)?></span>
			</span>
        <span class="con created"><?php print ($created)?></span>

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

//    while ($row = mysqli_fetch_assoc($resultcomments))
//    {
//        $nickName = $row['preferredName'];
//        $title = $row['title'];
//        $message = $row['message'];
//        $created = $row['created'];
        ?>


    <?php
//    }
    }
    }else {
    echo "Geen toegang voor niet-ingelogde gebruikers.";
    }

?>
