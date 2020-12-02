<?php
include __DIR__ . "/header.php";
$stmt = $Connection->prepare("SELECT SearchDetails FROM stockitems");
$stmt->execute();
$SearchDetails = [];
foreach ($stmt->get_result() as $row)
{
    $SearchDetails[] = metaphone($row['SearchDetails']);
}
print_r($SearchDetails);
$arrayCount = 0;
foreach ($SearchDetails as $SearchDetail) {
    $arrayCount = $arrayCount + 1;
    $Query = "UPDATE stockitems SET SearchDetails_soundslike = ? WHERE StockItemID = ?";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "si", $SearchDetail, $arrayCount);
    mysqli_stmt_execute($Statement);
}