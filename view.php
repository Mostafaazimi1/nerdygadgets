<?php
$Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($Connection, 'latin1');
include __DIR__ . "/header.php";

$Query = " 
           SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
            SearchDetails, 
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY StockItemID";

$ShowStockLevel = 1000;
$Statement = mysqli_prepare($Connection, $Query);
mysqli_stmt_bind_param($Statement, "i", $_GET['id']);
mysqli_stmt_execute($Statement);
$ReturnableResult = mysqli_stmt_get_result($Statement);
if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
    $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
} else {
    $Result = null;
}
//Get Images
$Query = "
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = ?";

$Statement = mysqli_prepare($Connection, $Query);
mysqli_stmt_bind_param($Statement, "i", $_GET['id']);
mysqli_stmt_execute($Statement);
$R = mysqli_stmt_get_result($Statement);
$R = mysqli_fetch_all($R, MYSQLI_ASSOC);

if ($R) {
    $Images = $R;
}



?>
<div id="CenteredContent">
    <?php
    if ($Result != null) {
        ?>
        <?php
        if (isset($Result['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $Result['Video']; ?>
            </div>
        <?php }
        ?>

        <h1 class="StockItemNameViewSize StockItemName"><?php print $Result['StockItemName'];?></h1>
        <div class="viewMainHeader row">
            <div class="ImageViewHeader">
                <?php
                if (isset($Images)) {
                    // print Single
                    if (count($Images) == 1) {
                        ?>
                        <div>
                            <img src="Public/StockItemIMG/<?php print $Images[0]['ImagePath']; ?>">
                        </div>
                        <?php
                    } else if (count($Images) >= 2) { ?>
                        <div id="ImageFrame">
                            <div id="ImageCarousel" class="carousel slide" data-interval="false">
                                <!-- Indicators -->
                                <ul class="carousel-indicators">
                                    <?php for ($i = 0; $i < count($Images); $i++) {
                                        ?>
                                        <li data-target="#ImageCarousel"
                                            data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                        <?php
                                    } ?>
                                </ul>

                                <!-- The slideshow -->
                                <div class="carousel-inner">
                                    <?php for ($i = 0; $i < count($Images); $i++) {
                                        ?>
                                        <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                            <img src="Public/StockItemIMG/<?php print $Images[$i]['ImagePath'] ?>">
                                        </div>
                                    <?php } ?>
                                </div>

                                <!-- Left and right controls -->
                                <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </a>
                                <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('Public/StockGroupIMG/<?php print $Result['BackupImagePath']; ?>'); background-size: cover;"></div>
                    <?php
                }
                ?>
            </div>
            <div class="InfoViewHeader">
                <div class="PrijsEnAfrekenenDiv">
                    <div class="PrijsEnAfrekenenChild">
                        <p class="StockItemPriceText"><b><?php print sprintf("€ %.2f", $Result['SellPrice']); ?></b></p>
                        <p> Inclusief BTW </p>
                        <div class="VoorraadText"><?php if (isset($Result['QuantityOnHand']) >= 1000){ echo "?> <p class="voorraad">Ruime voorraad</p>");} else { print ($Result['QuantityOnHand']); }?></div><br>
                        <form action="add.php" method="post">
                            <input type="hidden" name="action" value="submit" />
                            Aantal<br><input type="text" name="aantal" value="1" style="margin-bottom: 12px;">
                            <button class="bestelling-btn" type="submit" name="addcart" value="<?php print $Result['StockItemID']?>"><i class="fas fa-shopping-cart" style="color:#FFFFFF; padding-right: 7px;" aria-hidden="true"></i>Toevoegen aan winkelwagen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="ExtraInfoView row">
            <div class="productInformatie">
                <h3>Productinformatie</h3>
                <p><?php print $Result['SearchDetails']; ?></p>
            </div>
            <div class="productSpecs">
                <h3>Specificaties</h3>
                <?php
                $CustomFields = json_decode($Result['CustomFields'], true);
                if (is_array($CustomFields)) { ?>
                    <table>
                    <thead>
                    <th>Naam</th>
                    <th>Data</th>
                    </thead>
                    <?php
                    foreach ($CustomFields as $SpecName => $SpecText) { ?>
                        <tr>
                            <td>
                                <?php print $SpecName; ?>
                            </td>
                            <td>
                                <?php
                                if (is_array($SpecText)) {
                                    foreach ($SpecText as $SubText) {
                                        print $SubText . " ";
                                    }
                                } else {
                                    print $SpecText;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>Artikelnummer</td>
                        <td><?php print $Result["StockItemID"]; ?></td>
                    </tr>
                    </table><?php
                } else { ?>

                    <p><?php print $Result['CustomFields']; ?>.</p>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>
</div>
