<?php
include __DIR__ . "/header.php";
if (isset ($_SESSION['login']) && isset($_POST['edit'])) {
    $idNr = $_POST['edit'];
    $editStatus = $_POST['edit_status'];

    $Query = "
        UPDATE tickets SET status= '$editStatus' WHERE id= '$idNr'";
    $test = mysqli_query($Connection,$Query);

    if (!$test) {
        print("...");
    }
}

if (isset ($_SESSION['login'])) {
    $Medewerkerlogin = $_SESSION['login'];
    if ($Medewerkerlogin['IsSalesperson'] == 1) {
        //je bent gemachtigd om dit te zien!
        ?>
        <h1>Ticketing System</h1>
        <table id="listTickets" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Subject</th>
                <th>message</th>
                <th>created</th>
                <th>Status</th>
                <th>Edit status</th>
                <th>Comment ticket</th>
                <th></th>
            </tr>
            </thead>
        <tbody>
        <?php
        $Query = "
        SELECT t.id, p.preferredName, t.title, t.message, t.created, t.status
        FROM tickets t JOIN people p ON t.personID = p.personID";
        $result = mysqli_query($Connection, $Query);

        while ($row = mysqli_fetch_assoc($result))
        {
            $id = $row['id'];
         $nickName = $row['preferredName'];
         $title = $row['title'];
         $message = $row['message'];
         $created = $row['created'];
         $status = $row['status'];

   ?>

                 <tr>
                    <td><?php print($nickName)?></td>
                    <td><?php print($title)?></td>
                    <td><?php print($message)?></td>
                    <td><?php print($created)?></td>
                    <td><form action="servicedesk.php" method="POST">
                            <select class="form-control" name="edit_status" required>
                                <option value="open">open</option>
                                <option value="closed">closed</option>
                                <option value="resolved">resolved</option>
                            </select>
                            <button class="btn btn-primary" type="submit" name="edit" value="<?php echo ($id)?>">Edit</button>
                        </form>
                    </td>

                <?php
                if ($status == "open") {
                    print("<td class='far fa-clock'>$status</td>");
                } elseif ($status == "resolved") {
                    print("<td class='fas fa-check'>$status</td>");
                } elseif ($status == "closed") {
                    print("<td class='fas fa-times'>$status</td>");
                }
                ?>

                     <td><a href="reactie.php?reactie=<?php echo $id; ?>" class="btn btn-primary">Reactie</a></td>

        <?php
        }
        ?>

                 </tr>
        </tbody>
        </table>

<?php
    }
    else{
        //je bent NIET gemachtigd om dit te zien!
        echo "<p style='font-size: 20px; margin-top: 24px;'>Helaas leuk geprobeert, maar U heeft GEEN toegang!</p>";
    }
}
else{
    //je bent NIET gemachtigd om dit te zien!
    echo "<p style='font-size: 20px; margin-top: 24px;'>Helaas leuk geprobeert, maar U heeft GEEN toegang!</p>";
}

?>

