<?php
include __DIR__ . "/header.php";

if (isset ($_SESSION['login'])) {
    $Medewerkerlogin = $_SESSION['login'];
    if ($Medewerkerlogin['IsSalesperson'] == 1) {

        $RunQuery=FALSE;
        if (isset($_POST['Discount_Reset'])) {
            $UpdateDiscount_ID = ($_POST['Discount_Reset']);
            $NewDiscount = 0;
            $RunQuery = TRUE;
        }
        if (isset($_POST['Discount_Set'])) {
            $UpdateDiscount_ID = ($_POST['Discount_Set']);
            $NewDiscount = ($_POST['Discount_value']);
            if($NewDiscount>=100){
                $NewDiscount=0;
            }
            $RunQuery = TRUE;
        }
        if ($RunQuery == TRUE){
            $DiscountUpdate = mysqli_prepare($Connection, "UPDATE stockitems SET  Korting=(?) WHERE StockItemID=(?)");
            mysqli_stmt_bind_param($DiscountUpdate, 'ii',$NewDiscount, $UpdateDiscount_ID );
            mysqli_stmt_execute($DiscountUpdate);
            print('<meta http-equiv = "refresh" content = "0; url = ./sales.php" />');
        }

$SearchString = "";
$ReturnableResult = null;
if (isset($_GET['search_string'])) {
    $SearchString = $_GET['search_string'];
}
if (isset($_GET['category_id'])) {
    $CategoryID = $_GET['category_id'];
} else {
    $CategoryID = "";
}
if (isset($_GET['sort'])) {
    $SortOnPage = $_GET['sort'];
    $_SESSION["sort"] = $_GET['sort'];
} else if (isset($_SESSION["sort"])) {
    $SortOnPage = $_SESSION["sort"];
} else {
    $SortOnPage = "price_low_high";
    $_SESSION["sort"] = "price_low_high";
}

if (isset($_GET['products_on_page'])) {
    $ProductsOnPage = $_GET['products_on_page'];
    $_SESSION['products_on_page'] = $_GET['products_on_page'];
} else if (isset($_SESSION['products_on_page'])) {
    $ProductsOnPage = $_SESSION['products_on_page'];
} else {
    $ProductsOnPage = 25;
    $_SESSION['products_on_page'] = 25;
}
if (isset($_GET['page_number'])) {
    $PageNumber = $_GET['page_number'];
} else {
    $PageNumber = 0;
}

$AmountOfPages = 0;
$queryBuildResult = "";
switch ($SortOnPage) {
    case "price_high_low":
    {
        $Sort = "SellPrice DESC";
        break;
    }
    case "name_low_high":
    {
        $Sort = "StockItemName";
        break;
    }
    case "name_high_low";
        $Sort = "StockItemName DESC";
        break;
    case "price_low_high":
    {
        $Sort = "SellPrice";
        break;
    }
    case"id_low_high":
    {
        $Sort = "StockItemID";
        break;
    }
    case"id_high_low":
        {
            $Sort = "StockitemID DESC";
            break;
        }
    case"discount_high_low":
    {
        $Sort = "korting DESC";
        break;
    }
    default:
    {
        $Sort = "SellPrice";
        $SortName = "price_low_high";
    }
}
if ($SearchString != "") {
    $exploded = explode(" ", $SearchString);
    $searchValues = [];
    foreach ($exploded as $val) {
        $trimmed = trim($val);
        if ($trimmed != "") {
            array_push($searchValues, $val);
        }
    }

// worden ze omgevormd tot script enkel gebaseerd op klank
// $searchValues = array_map(function ($val) {return metaphone($val);}, $searchValues);
    $searchValues = array_map(function ($val) {
        if (is_numeric($val)) {
            return $val;
        } else {
            return metaphone($val);
        }
    }, $searchValues);


    $queryBuildResult = "";
    if (count($searchValues) > 0) {
        $queryBuildResult .= "WHERE ";
        for ($i = 0; $i < count($searchValues); $i++) {
            if ($i != 0) {
                $queryBuildResult .= " OR ";
            }

            if (!is_numeric($searchValues[$i])) {
                $queryBuildResult .= "SI.SearchDetails_soundslike LIKE '%$searchValues[$i]%'";
            } else {
                $queryBuildResult .= "SI.StockItemID = '$searchValues[$i]'";
            }
        }
    }
}

$Offset = $PageNumber * $ProductsOnPage;

$ShowStockLevel = 1000;
$OutOfStock = 0;
if ($CategoryID == "") {

    $Query = "
                SELECT SI.StockItemID, SI.StockItemName, SI.MarketingComments, ROUND(TaxRate * RecommendedRetailPrice / 100 + RecommendedRetailPrice,2) as SellPrice, korting,
                (CASE WHEN (SIH.QuantityOnHand) >= ? THEN CONCAT('Ruime voorraad beschikbaar. Voorraad: ',QuantityOnHand) 
                WHEN (SIH.QuantityOnHand) <= ? THEN CONCAT('Helaas, dit product is uitverkocht. Voorraad: ',QuantityOnHand) 
                WHEN (SIH.QuantityOnHand) < 100 THEN CONCAT('Let op, bijna uitverkocht! Voorraad: ',QuantityOnHand) 
                ELSE CONCAT('Voorraad: ',QuantityOnHand) END) AS QuantityOnHand, 
                (SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = SI.StockItemID LIMIT 1) as ImagePath,
                (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath
                FROM stockitems SI
                JOIN stockitemholdings SIH USING(stockitemid)
                " . $queryBuildResult . "
                GROUP BY StockItemID
                ORDER BY " . $Sort . " 
                LIMIT ?  OFFSET ?";

    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "iiii", $ShowStockLevel, $OutOfStock, $ProductsOnPage, $Offset);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);

    $Query = "
            SELECT count(*)
            FROM stockitems SI
            $queryBuildResult";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);
} else {

    if ($queryBuildResult != "") {
        $queryBuildResult .= " AND ";
    }

    $Query = "
                SELECT SI.StockItemID, SI.StockItemName, SI.MarketingComments, 
                ROUND(SI.TaxRate * SI.RecommendedRetailPrice / 100 + SI.RecommendedRetailPrice,2) as SellPrice, korting, 
                (CASE WHEN (SIH.QuantityOnHand) >= ? THEN CONCAT('Ruime voorraad beschikbaar. Voorraad: ',QuantityOnHand) 
                WHEN (SIH.QuantityOnHand) <= ? THEN CONCAT('Helaas, dit product is uitverkocht. Voorraad: ',QuantityOnHand) 
                WHEN (SIH.QuantityOnHand) < 100 THEN CONCAT('Let op, bijna uitverkocht! Voorraad: ',QuantityOnHand) 
                ELSE CONCAT('Voorraad: ',QuantityOnHand) END) AS QuantityOnHand, 
                (SELECT ImagePath FROM stockitemimages WHERE StockItemID = SI.StockItemID LIMIT 1) as ImagePath,
                (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath           
                FROM stockitems SI 
                JOIN stockitemholdings SIH USING(stockitemid)
                JOIN stockitemstockgroups USING(StockItemID)
                JOIN stockgroups ON stockitemstockgroups.StockGroupID = stockgroups.StockGroupID
                WHERE " . $queryBuildResult . " ? IN (SELECT StockGroupID from stockitemstockgroups WHERE StockItemID = SI.StockItemID)
                GROUP BY StockItemID
                ORDER BY " . $Sort . " 
                LIMIT ? OFFSET ?";

    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "iiiii", $ShowStockLevel, $OutOfStock, $CategoryID, $ProductsOnPage, $Offset);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    $ReturnableResult = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);

    $Query = "
                SELECT count(*)
                FROM stockitems SI 
                WHERE " . $queryBuildResult . " ? IN (SELECT SS.StockGroupID from stockitemstockgroups SS WHERE SS.StockItemID = SI.StockItemID)";
    $Statement = mysqli_prepare($Connection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $CategoryID);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);
}
$amount = $Result[0];
if (isset($amount)) {
    $AmountOfPages = ceil($amount["count(*)"] / $ProductsOnPage);
}

//push push push


?>
<div id="FilterFrame"><h4 class="FilterText">Filteren</h4>
    <form>
        <div id="FilterOptions">
            <p class="FilterTopMargin">Product zoeken</p>
            <input type="text" name="search_string" id="search_string"
                   value="<?php print (isset($_GET['search_string'])) ? $_GET['search_string'] : ""; ?>"
                   class="form-submit">
            <p class="FilterTopMargin">Aantal producten op pagina</p>
            <input type="hidden" name="category_id" id="category_id"
                   value="<?php print (isset($_GET['category_id'])) ? $_GET['category_id'] : ""; ?>">
            <select name="products_on_page" id="products_on_page" onchange="this.form.submit()">>
                <option value="25" <?php if ($_SESSION['products_on_page'] == 25) {
                    print "selected";
                } ?>>25
                </option>
                <option value="50" <?php if ($_SESSION['products_on_page'] == 50) {
                    print "selected";
                } ?>>50
                </option>
                <option value="75" <?php if ($_SESSION['products_on_page'] == 75) {
                    print "selected";
                } ?>>75
                </option>
            </select>
            <p class="FilterTopMargin">Producten sorteren</p>
            <select name="sort" id="sort" onchange="this.form.submit()">>
                <option value="price_low_high" <?php if ($_SESSION['sort'] == "price_low_high") {
                    print "selected";
                } ?>>Prijs oplopend
                </option>
                <option value="price_high_low" <?php if ($_SESSION['sort'] == "price_high_low") {
                    print "selected";
                } ?> >Prijs aflopend
                </option>
                <option value="name_low_high" <?php if ($_SESSION['sort'] == "name_low_high") {
                    print "selected";
                } ?>>Naam oplopend
                </option>
                <option value="name_high_low" <?php if ($_SESSION['sort'] == "name_high_low") {
                    print "selected";
                } ?>>Naam aflopend
                </option>
                <option value="id_low_high" <?php if ($_SESSION['sort'] == "id_low_high") {
                    print "selected";
                } ?>>ID oplopend
                </option>
                <option value="id_high_low" <?php if ($_SESSION['sort'] == "id_high_low") {
                    print "selected";
                } ?>>ID aflopend
                </option>
                <option value="discount_high_low" <?php if ($_SESSION['sort'] == "discount_high_low") {
                    print "selected";
                } ?>>Korting
                </option>
            </select>
    </form>
</div>
</div>
<div id="ResultsArea" class="Browse">
    <h1></h1>
    <?php
    if (isset($ReturnableResult) && count($ReturnableResult) > 0) {
        foreach ($ReturnableResult as $row) {

            $retailPrice= $row['SellPrice'];
            $sellPrice = round($row['SellPrice']*((100-$row['korting'])/100), 2);
            $discount=($row['korting']);

            ?>
                <div class="naastElkaar" id="ProductFrame">
                    <div class="productFrameLinks naastElkaar" id="geenPadding">
                        <div class="productFrameLinksImage">
                            <?php if (isset($row['ImagePath'])) {
                                if ($row['ImagePath'] == "") {?>
                                    <img class="ImgFrame" src="<?php print "Public/StockGroupIMG/" . $row['BackupImagePath'] ?>">
                                <?php } else {
                                    print("<img class= 'ImgFrame' src= 'Public/StockItemIMG/" . $row['ImagePath'] . "'>");
                                }
                            }  else if (isset($row['BackupImagePath'])) { ?>
                                <a href='view.php?id=<?php print $row['StockItemID']; ?>'>
                                    <img class="ImgFrame" src="<?php print "Public/StockGroupIMG/" . $row['BackupImagePath'] ?>"></a>
                            <?php } ?>
                        </div>
                        <div class="productFrameLinksInfo">
                            <div>
                                <p class="StockItemID">Artikelnummer: <?php print $row["StockItemID"]; ?></p>
                                <h3 class="StockItemName"><?php print $row["StockItemName"]; ?></h3>
                                <p class="ItemQuantity"><?php print $row["QuantityOnHand"]; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="productFrameRechts">
                        <div id="StockItemFrameRight">
                            <div class="CenterPriceLeftChild">
                                <?php
                                if ($discount>0){
                                    print("<p class='Advice'> Adviesprijs</p>");
                                    print("<p class='RetailPriceSale'> ". sprintf("€ %0.2f", $retailPrice). "</p>");
                                }
                                if (isset($_post['']))
                                ?>
                                <p class="StockItemPriceText"><?php print sprintf("€ %0.2f", $sellPrice); ?></p>
                                <form method="post" action="sales.php">

                                    <button type="submit" name="Discount_Reset"
                                            value="<?php print $row['StockItemID']; ?>"> RESET
                                    </button>

                                    <input type="number" name="Discount_value" placeholder='%' min="0" max="99">

                                    <button type="submit" name="Discount_Set"
                                            value="<?php print $row['StockItemID']; ?>"> Opslaan
                                    </button>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
        <?php
        }


        ?>




        <form id="PageSelector">
            <input type="hidden" name="search_string" id="search_string"
                   value="<?php if (isset($_GET['search_string'])) {
                       print ($_GET['search_string']);
                   } ?>">
            <input type="hidden" name="category_id" id="category_id" value="<?php if (isset($_GET['category_id'])) {
                print ($_GET['category_id']);
            } ?>">
            <input type="hidden" name="result_page_numbers" id="result_page_numbers"
                   value="<?php print (isset($_GET['result_page_numbers'])) ? $_GET['result_page_numbers'] : "0"; ?>">
            <input type="hidden" name="products_on_page" id="products_on_page"
                   value="<?php print ($_SESSION['products_on_page']); ?>">
            <input type="hidden" name="sort" id="sort" value="<?php print ($_SESSION['sort']); ?>">

            <?php
            if ($AmountOfPages > 0) {
                for ($i = 1; $i <= $AmountOfPages; $i++) {
                    if ($PageNumber == ($i - 1)) {
                        ?>
                        <button id="page_number" class="PageNumberActief"
                                name="page_number"><?php print($i); ?></button><?php
                    } else { ?>
                        <button id="page_number" class="PageNumber" value="<?php print($i - 1); ?>" type="submit"
                                name="page_number"><?php print($i); ?></button>
                    <?php }
                }
            }
            ?>
        </form>
        <?php
    } else {
        ?>
        <h2 id="NoSearchResults">
            Yarr, er zijn geen resultaten gevonden.
        </h2>
        <?php
    }
    ?>
</div>
<?php
    }
    else{
        //je bent NIET gemachtigd om dit te zien!
        echo "<p style='font-size: 20px; margin-top: 24px;'>Helaas leuk geprobeerd, maar U heeft GEEN toegang!</p>";
    }

}
else{
    //je bent NIET gemachtigd om dit te zien!
    echo "<p style='font-size: 20px; margin-top: 24px;'>Helaas leuk geprobeerd, maar U heeft GEEN toegang!</p>";
}

?>