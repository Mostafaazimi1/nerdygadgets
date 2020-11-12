<?php
include __DIR__ . "/header.php";
$winkelwagen = array(
    array(
        'id' => 1,
        'aantal' => 3
    ),
    array(
        'id' => 6,
        'aantal' => 2
    ),
    array(
        'id' => 123,
        'aantal' => 6
    ),
    array(
        'id' => 1,
        'aantal' => 3
    ),
);

function loadProducts($winkelwagen, $conn)
{
    $selectIds = array();

    foreach ($winkelwagen as $item) {
        array_push($selectIds, $item['id']);
    }

    $sql = "SELECT s.StockItemName name, s.UnitPrice, s.StockItemID, si.ImagePath
            FROM stockitems s
            JOIN stockitemimages si on s.StockItemID = si.StockItemID";

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
                    $newWinkelWagen[$key]['price'] = $row['UnitPrice'];
                }
            }
        }
    }

    return $newWinkelWagen;
}

$products = loadProducts($winkelwagen, $Connection);

?>


<div class="winkelmandje">
    <h1>Winkelmandje</h1>
    <div class="overzicht-wrapper">
        <div class="product-overzicht">
            <table>
                <tr>
                    <th>Foto</th>
                    <th>Title</th>
                    <th>Prijs</th>
                    <th>Aantal</th>
                    <th>Subtotaal</th>
                    <th>Verwijderen</th>
                </tr>

                <?php
                $allTotal = 0;
                foreach ($products as $product) {
                    $total = $product['price'] * $product['aantal'];
                    $allTotal += $total;
                    echo "<tr>";
                    echo "<td><img src='Public/StockItemIMG/" . $product['img'] . "' style='max-width: 30px'></td>";
                    echo "<td><p>" . $product['name'] . "</p></td>";
                    echo "<td><p>€" . $product['price'] . "</p></td>";
                    echo "<td><p>" . $product['aantal'] . "</p></td>";
                    echo "<td><p>€" . $total . "</p></td>";
                    echo "<td>X</td>";
                    echo "</tr>";
                }
                ?>

            </table>
        </div>

        <div class="prijs-overzicht">
            <h2>Prijs overizcht</h2>
            <table>
                <tr>
                    <td>Subtotaal</td>
                    <td class="td-geld table-rechts">€<?php echo $allTotal; ?>,-</td>
                </tr>
                <tr>
                    <td>Verzendkosten</td>
                    <td class="td-gratis-verz table-rechts">
                        <?php if($allTotal < 25){
                            echo "€6,25";
                        }else{
                            echo 'Gratis';
                        } ?>
                        </td>
                </tr>
            </table>
            <hr class="betalen-hr">
            <table>
                <tr>
                    <td>Totaalprijs</td>
                    <td class="td-geld table-rechts"> <?php if($allTotal < 25){
                            echo "€" . ($allTotal + 6.25);
                        }else{
                            echo "€" . $allTotal . ",-";
                        } ?></td>
                </tr>
            </table>
            <p>Inclusief btw</p>
            <button class="bestelling-btn">Bestelling afronden</button>
        </div>
    </div>
</div>
