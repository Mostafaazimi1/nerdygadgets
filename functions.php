<?php
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
                $found = TRUE;
                $newTotal = $amount;
                break;
            }
        }

        if (!$found) {
            array_push($_SESSION['winkelwagen'], $item);
            $newTotal = $aantal;
        }
    }

    return $newTotal;
}

function loadProducts($winkelwagen, $conn)
{
    if (count($winkelwagen) == 0) {
        return array();
    }
    $selectIds = array();

    foreach ($winkelwagen as $item) {
        array_push($selectIds, $item['id']);
    }

    $sql = "SELECT s.StockItemName name, s.UnitPrice, (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, s.StockItemID, si.ImagePath
            FROM stockitems s
            LEFT JOIN stockitemimages si on s.StockItemID = si.StockItemID";

    $where = " WHERE";

    foreach ($selectIds as $selectId) {
        $where .= " s.StockItemID = " . $selectId . " OR";
    }

    $where = substr($where, 0, -3);
    $sql = $sql . $where;


    $result = mysqli_query($conn, $sql);
    $newWinkelWagen = $winkelwagen;

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            foreach ($newWinkelWagen as $key => $winkelwagenItem) {
                if ($winkelwagenItem['id'] == $row['StockItemID']) {
                    $newWinkelWagen[$key]['name'] = $row['name'];
                    $newWinkelWagen[$key]['img'] = $row['ImagePath'];
                    $newWinkelWagen[$key]['price'] = number_format(round($row['SellPrice'], 2), 2);
                    break;
                }
            }
        }
    }

    return $newWinkelWagen;
}

function deleteProduct($winkelwagen, $id)
{
    foreach ($winkelwagen as $key => $winkelwagenItem) {
        if ($winkelwagenItem['id'] == $id) {
            unset($_SESSION['winkelwagen'][$key]);
            $winkelwagen = $_SESSION['winkelwagen'];
            break;
        }
    }

    return $winkelwagen;
}

function getCount($winkelwagen)
{
    $count = 0;
    foreach ($winkelwagen as $winkelwagenitem) {
        $count = $count + $winkelwagenitem['aantal'];
    }

    return $count;
}

function bestellingAfronden($winkelwagen, $afrekenGegevens)
{
    if(isset($afrekenGegevens['newAcc'])){
        //hier query voor maken van die acc
    }

    
}
