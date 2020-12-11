<?php
include __DIR__ . "/header.php";

if (isset ($_SESSION['login'])) {
    $Medewerkerlogin = $_SESSION['login'];
    if ($Medewerkerlogin['IsSalesperson'] == 1) {
        //je bent gemachtigd om dit te zien!


        
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