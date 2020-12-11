<?php
if (isset ($_SESSION['login'])) {
    $Medewerkerlogin = $_SESSION['login'];
    if ($Medewerkerlogin['IsSalesperson'] == 1) {
        //je bent gemachtigd om dit te zien!
    }
    else{
        //je bent NIET gemachtigd om dit te zien!
    }
}