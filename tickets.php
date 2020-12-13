<?php
include __DIR__ . "/header.php";

?>
        <h1>Ticketing System</h1>
        <table id="listTickets" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Extra</th>
                <th>Created</th>
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

                <tr><a href="viewticket.php?ticket=<?php echo($id);?>">
                        <td><a href="viewticket.php?ticket=<?php echo($id);?>"><?php print($nickName)?></a></td>
                        <td><a href="viewticket.php?ticket=<?php echo($id);?>"><?php print($title)?></a></td>
                        <?php
                        if ($status == "open") {
                            print("<td class='far fa-clock'><a href='viewticket.php?ticket=$id'>$status</td>");
                        } elseif ($status == "resolved") {
                            print("<td class='fas fa-check'><a href='viewticket.php?ticket=$id'>$status</td>");
                        } elseif ($status == "closed") {
                            print("<td class='fas fa-times'><a href='viewticket.php?ticket=$id'>$status</td>");
                        }
                        ?>
                        <td><a href="viewtickets.php?ticket=<?php echo $id; ?>" class="btn btn-primary">Extra</a></td>
                        <td><a href="viewtickets.php?ticket=<?php echo($id);?>"><?php print($created)?></td>
                    </a>
                </tr>
                <?php
            }
            ?>

            </tbody>
        </table>


<?php
include __DIR__ . "/footer.php";
?>