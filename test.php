<?php
$SearchString = "offis office ofis";
$searchValues = explode(" ", $SearchString);
// worden ze omgevormd tot script enkel gebaseerd op klank
$searchValues = array_map(function($val) { return metaphone($val); }, $searchValues);
$queryBuildResult = "";
if ($SearchString != "") {
    for ($i = 0; $i < count($searchValues); $i++) {
        if ($i != 0) {
            $queryBuildResult .= "AND ";
        }
        $queryBuildResult .= "SI.SearchDetails_soundslike LIKE '%$searchValues[$i]%' ";
    }
    if ($queryBuildResult != "") {
        $queryBuildResult .= " OR ";
    }
    if ($SearchString != "" || $SearchString != null) {
        $queryBuildResult .= "SI.StockItemID ='$SearchString'";
    }
}
print ($queryBuildResult);