<?php
include __DIR__ . "/header.php";

if (isset($_GET['ticket'])) {
    $id = $_GET['ticket'];
}

$Query= " SELECT t.id, p.preferredName, t.title, t.message, t.created, t.status
          FROM tickets t JOIN people p ON t.personID = p.personID
          WHERE id = '$id'";
$result = mysqli_query($Connection, $Query);


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
				<span class="title"><?=htmlspecialchars($title, ENT_QUOTES)?></span>
				<span class="msg"><?=htmlspecialchars($message, ENT_QUOTES)?></span>
			</span>
    <span class="con created"><?=date('F dS, G:ia', strtotime($created))?></span>




<?php

}
?>

