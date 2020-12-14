<?php
function addItem($id, $aantal, $max)
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
                if($amount <= $max){
                    $_SESSION['winkelwagen'][$key]['aantal'] = $amount;
                    break;
                }

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

    $sql = "SELECT s.StockItemName name, sh.QuantityOnHand, s.UnitPrice, (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, s.StockItemID, si.ImagePath, s.korting
            FROM stockitems s
            LEFT JOIN stockitemimages si on s.StockItemID = si.StockItemID
            LEFT JOIN stockitemholdings sh on sh.StockItemID = s.StockItemID";

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
                    $newWinkelWagen[$key]['aantalbeschikbaar'] = $row['QuantityOnHand'];
                    $newWinkelWagen[$key]['price'] = number_format(round($row['SellPrice'], 2), 2);
                    $newWinkelWagen[$key]['korting'] = $row['korting'];
                    $newWinkelWagen[$key]['kortingc'] = ((100 - $row['korting']) / 100);
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

function loadProductsByTag($tags, $connection)
{
    $like = 'LIKE ';
    foreach ($tags as $tag) {
        $like .= "'%" . $tag . "%' OR ";
    }

    $like = substr($like, 0, -3);

    $sql = "SELECT s.StockItemID, s.RecommendedRetailPrice, s.korting, s.StockItemName, si.ImagePath
            FROM stockitems s
            JOIN stockitemimages si on s.StockItemID = si.StockItemID
            WHERE s.Tags " . $like . "
            ORDER BY RAND() LIMIT 3";

    $result = mysqli_query($connection, $sql);
    $items = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $item = array(
                'id' => $row['StockItemID'],
                'name' => $row['StockItemName'],
                'price' => $row['RecommendedRetailPrice'],
                'img' => $row['ImagePath'],
                'korting' => $row['korting']
            );

            array_push($items, $item);
        }
    }

    return $items;
}

function getCount($winkelwagen)
{
    $count = 0;
    foreach ($winkelwagen as $winkelwagenitem) {
        $count = $count + $winkelwagenitem['aantal'];
    }

    return $count;
}

function updateAmount($id, $amount, $winkelwagen)
{
    foreach ($winkelwagen as $key => $winkelwagenItem) {
        if ($winkelwagenItem['id'] == $id) {
            if ($amount == 0 || $amount < 0) {
                unset($_SESSION['winkelwagen'][$key]);
            } else {
                // if($amount < $_SESSION['winkelwagen'][$key]['aantalbeschikbaar']){
                $_SESSION['winkelwagen'][$key]['aantal'] = $amount;
                //  }
            }
            break;
        }
    }
}

function bestellingAfronden($winkelwagen, $afrekenGegevens)
{
    if (isset($afrekenGegevens['newAcc'])) {
        //hier query voor maken van die acc
    }
}

function getTemprature($connection, $sensorNumber)
{
    $sql = "SELECT Temperature FROM coldroomtemperatures WHERE ColdRoomSensorNumber = " . $sensorNumber;

    $result = mysqli_query($connection, $sql);
    $temperature = 0;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
           $temperature = $row['Temperature'];
        }
    }

    return $temperature;
}
function PostcodeCheck($postcode)
{
    $remove = str_replace(" ","", $postcode);
    $upper = strtoupper($remove);

    if( preg_match("/^\b[1-9]\d{3}\s*[A-Z]{2}\b$/",  $upper)) {
        return TRUE;
    } else {
        return FALSE;
    }
}