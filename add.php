<?php
if (isset($_POST["action"])) {
    session_start();

    $productID = $_POST["addcart"];
    $aantal = $_POST["aantal"];

    $total = addItem($productID, $aantal);

    header("Location: view.php?id=" . $productID . "&amount=". $total ."&succes=true");
    die();
}

function addItem($id, $aantal)
{
    if (isset($_SESSION['winkelwagen'])) {
        $item = array(
            'id' => $id,
            'aantal' => $aantal
        );

        $newTotal = 0;
        $found = FALSE;
        foreach ($_SESSION['winkelwagen'] as $key => $winkelwagenItem) {
            if ($winkelwagenItem['id'] == $id) {
               $amount = $_SESSION['winkelwagen'][$key]['aantal'];
               $amount = $amount + $aantal;
               $_SESSION['winkelwagen'][$key]['aantal'] = $amount;
               $fount = TRUE;
               $newTotal = $amount;
               break;
            }
        }

        if(!$found){
            array_push($_SESSION['winkelwagen'], $item);
            $newTotal = $aantal;
        }
    }

    return $newTotal;
}