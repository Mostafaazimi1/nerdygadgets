<?php
include __DIR__ . "/header.php";
$FullName = "kaasje kaas";
$sql = "
                SELECT FullName
                FROM people
                WHERE FullName = '" . $FullName . "'";
$result = $Connection->query($sql);
$aantalresult = mysqli_num_rows($result);
if ($aantalresult < 1) {
    $validName = TRUE;
} else {
    echo("Sorry, de naam " . $FullName . " is al in gebruik.<br>");
}
