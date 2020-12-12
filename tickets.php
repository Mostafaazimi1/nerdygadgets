<?php
include __DIR__ . "/header.php";

$Query = "
        SELECT p.preferredName, t.title, t.message, t.created, t.status
        FROM tickets t
        JOIN people p 
        ON t.personID = p.personID";
        $result = mysqli_query($Connection, $Query);

while ($row = mysqli_fetch_assoc($result))
{
    $nickName = $row['preferredName'];
    $title = $row['title'];
    $message = $row['message'];
    $created = $row['created'];
    $status = $row['status'];
    ?>
    <div class="table-responsive col-lg-12">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <td><?php print($nickName)?></td>
                <td><?php print($title)?></td>
                <td><?php print($message)?></td>
                <td><?php print($created)?></td>
                <td><?php print($status)?></td>
            </tr>
        </table>
    </div>


        <?php
        }