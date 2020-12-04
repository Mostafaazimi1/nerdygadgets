<?php
$Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($Connection, 'latin1');
include __DIR__ . "/header.php";

$Query = " 
           SELECT SI.StockItemID, SI.Tags, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            CONCAT(QuantityOnHand)AS QuantityOnHand,
            korting,
            QuantityOnHand AS aantal,
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
$itemID = $_GET['id'];
mysqli_stmt_bind_param($Statement, "i", $itemID);
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
mysqli_stmt_bind_param($Statement, "i", $itemID);
mysqli_stmt_execute($Statement);
$R = mysqli_stmt_get_result($Statement);
$R = mysqli_fetch_all($R, MYSQLI_ASSOC);

$sql1 = "SELECT COUNT(*) AS total, AVG(rating) AS avgrating FROM `review` WHERE StockItemID = " . $itemID;
$result1 = $Connection->query($sql1);
if ($result1->num_rows > 0) {
    while ($row1 = $result1->fetch_assoc()) {
        // Voeg bijvehorende gegevens van de klant toe aan de array uit customers tabel
        $itemCount = $row1["total"];
        $avgRating = (int)$row1["avgrating"];
        continue;
    }
}

//$sql2 = "SELECT AVG(Rating) avgrating FROM `review` WHERE StockItemID = ".$itemID;
//$result1 = $Connection->query($sql1);
//if ($result1->num_rows > 0) {
////                    $row1 = $result1->fetch_assoc();
//    $itemCount = $result1->fetch_assoc()["total"];
//}

if ($R) {
    $Images = $R;
}

// START VAN VIEW HTML

if ($Result != null) {
    ?>
    <!--    <div id="VideoFrame">-->
    <!--        --><?php //print $Result['Video']; ?>
    <!--    </div>-->
    <?php //}

    $tags = str_replace('"', '', substr($Result['Tags'], 1, -1));
    $tagsArray = explode(",", $tags);
    $sameProducts = loadProductsByTag($tagsArray, $Connection);
//?>
    <h1 class="StockItemNameViewSize StockItemName"><?php print $Result['StockItemName'];?></h1>
    <div class="naastElkaar reviewUnderH1">
        <div class="SterrenH1">
            <div>
                <?php
                $ratingStars = "";
                $avgRatingvar = $avgRating;
                for ($i3 = 0; $i3 < 5; $i3++) {
                    if ($avgRatingvar > 0) {
                        $ratingStars .= '<i class="fas fa-star"></i>';
//                        print('<i class="fas fa-star"></i>');
                    } else {
                        $ratingStars .= '<i class="far fa-star"></i>';
//                        print('<i class="far fa-star"></i>');
                    }
                    $avgRatingvar--;
                }
                print($ratingStars); ?>
            </div>
        </div>
        <div class="TextSterrenH1">
            <p><?php print($itemCount); ?> reviews</p>
        </div>
    </div>
    <div class="viewMainHeader naastElkaar">
        <div class="ImageViewHeader">
            <?php
            if (isset($Images)) {
                // print Single
            if (count($Images) == 1) {
                ?>
                <div id="ProductImage">
                    <img src="Public/StockItemIMG/<?php print $Images[0]['ImagePath']; ?>">
                </div>
            <?php
            } else if (count($Images) >= 2) { ?>
                <div class="product__carousel">
                    <div class="gallery-parent">
                        <!-- SwiperJs and EasyZoom plugins start -->
                        <div class="swiper-container gallery-top">
                            <div class="swiper-wrapper">
                                <?php for ($i = 0; $i < count($Images); $i++) {
                                    ?>
                                    <div class="swiper-slide easyzoom easyzoom--overlay">
                                        <a href="Public/StockItemIMG/<?php print $Images[$i]['ImagePath'] ?>">
                                            <img src="Public/StockItemIMG/<?php print $Images[$i]['ImagePath'] ?>">
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php
                                if (isset($Result['Video'])) {
                                    ?>
                                    <!--                                                        VIDEO IN CAROUSEL MOET NOG FIXEN-->
                                    <!--                                                <div class="swiper-slide">-->
                                    <!--                                                    <div id="CarouselVideoFrame">-->
                                    <!--                                                        --><?php //print $Result['Video']; ?>
                                    <!--                                                    </div>-->
                                <?php }
                                ?>
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        <div class="swiper-container gallery-thumbs">
                            <div class="swiper-wrapper">
                                <?php for ($i = 0; $i < count($Images); $i++) {
                                    ?>
                                    <div class="swiper-slide">
                                        <img src="Public/StockItemIMG/<?php print $Images[$i]['ImagePath'] ?>" alt=""/>
                                    </div>
                                <?php } ?>

                                <!--VIDEO IN CAROUSEL MOET NOG FIXEN-->
                                <!--                                        --><?php
                                //                                        if (isset($Result['Video'])) {
                                //                                        ?>
                                <!--                                            <div class="swiper-slide">-->
                                <!--                                                <img src="Public/Img/online-video.png" alt="" />-->
                                <!--                                            </div>-->
                                <!--                                        --><?php //} ?>
                            </div>
                        </div>
                        <!-- SwiperJs and EasyZoom plugins end -->
                    </div>
                </div>
                <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                <script src="Public/JS/swiper.min.js"></script>
                <script src="Public/JS/easyzoom.js"></script>
                <script src="Public/JS/main.js"></script>
            <?php }
            } else {
            ?>
                <div id="ProductImage">
                    <img src="Public/StockGroupIMG/<?php print $Result['BackupImagePath']; ?>">
                </div>

                <?php
            }
            ?>
        </div>
        <div class="InfoViewHeader">
            <div class="PrijsEnAfrekenenDiv">
                <div class="PrijsEnAfrekenenChild">
                    <?php
                    //korting of geen korting
                    $discount=$Result['korting'];
                    $retailPrice = $Result['SellPrice'];
                    $sellPrice= round($Result['SellPrice']*((100-$discount)/100), 2);

                    if ($discount>0){
                        print("<p class='Advice'> Adviesprijs</p>");
                        print("<p class='RetailPrice'>". sprintf("€ %0.2f", $retailPrice). "</p>");
                    }
                    ?>
                    <p class="StockItemPriceText"><b><?php print sprintf("€ %.2f", $sellPrice); ?></b></p>
                    <p> Inclusief BTW </p>

                    <div class="VoorraadText"> <?php
                        $voorraadbeschikbaartext = (int)$Result['QuantityOnHand'];
                        if ($voorraadbeschikbaartext >= 1000) {
                            echo("<p class='voorraad'><i class='fas fa-box' style='color:#2BAE49; padding-right: 7px;' aria-hidden='true'></i>Ruime voorraad beschikbaar.</p>"); //Big stock above 1000
                        } elseif ($voorraadbeschikbaartext <= 0) {
                            echo("<p class='voorraad' style='color:#ff0000 !important;'><i class='fas fa-box' style='color:#ff0000 !important; padding-right: 7px;' aria-hidden='true'></i>UITVERKOCHT</p>"); //Sold out
                        } elseif ($voorraadbeschikbaartext > 0 && $voorraadbeschikbaartext < 100) {
                            echo("<p class='voorraad' style='color:#ffa500 !important;'><i class='fas fa-box' style='color:#ffa500 !important; padding-right: 7px;' aria-hidden='true'></i>Bijna uitverkocht. Voorraad: " . $Result['QuantityOnHand'] . "</p>"); //Stock almost sold out
                        } else {
                            echo("<p class='voorraad'><i class='fas fa-box' style='color:#2BAE49; padding-right: 7px;' aria-hidden='true'></i>Voorraad: " . $Result['QuantityOnHand'] . "</p>"); //Show stock
                        }
                        ?>
                    </div>
                    <?php if ($Result['aantal'] > 0) { ?>
                        <br>
                        <form action="add.php" method="post">
                            <input type="hidden" name="action" value="submit"/>
                            Aantal<br><input type="number" name="aantal" min="0" value="1"
                                             max="<?php echo $Result['aantal']; ?>" style="margin-bottom: 12px;">
                            <button class="bestelling-btn" type="submit" name="addcart"
                                    value="<?php print $Result['StockItemID'] ?>"><i class="fas fa-shopping-cart"
                                                                                     style="color:#FFFFFF; padding-right: 7px;"
                                                                                     aria -></i> Toevoegen
                                aan winkelwagen
                            </button>
                        </form>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <div class="ExtraInfoView naastElkaar">
        <div class="productInformatie">
            <h3>Productinformatie</h3>
            <p><?php print $Result['MarketingComments']; ?></p>
        </div>
        <div class="productSpecs">
            <h3>Specificaties</h3>
            <?php
            $CustomFields = json_decode($Result['CustomFields'], true);
            if (is_array($CustomFields)) { ?>
                <table>
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
    <div>
        <div>
            <h3>Andere producten</h3>
        </div>
        <div>
            <?php
            foreach ($sameProducts as $product) {
                ?>
                <a class="ListItem" href="view.php?id=<?php echo $product['id']; ?>">
                    <div class="naastElkaar" id="ProductFrame"
                         style="width: 31%; margin-left: 1%; margin-right: 1%; float:left;">
                        <div class="productFrameLinks naastElkaar" id="geenPadding" style="width: 100%">
                            <div class="productFrameLinksImage"><img class="ImgFrame" src="Public/StockItemIMG/<?php echo $product['img']; ?>"
                                                                     style="max-width: 100%; max-height: 100%;">
                            </div>
                            <div class="productFrameLinksInfo" style="margin-left: 5%; width: 65%;">
                                <h3 class="StockItemName"><?php echo $product['name']; ?></h3>
                            </div>
                        </div>
                        <div class="productFrameRechts" style="width: 100%">
                            <div id="StockItemFrameRight">
                                <div class="CenterPriceLeftChild">
                                    <p class="StockItemPriceText">€ <?php echo $product['price']; ?></p>
                                    <p class="StockItemBTW">Inclusief BTW </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="reviews">
        <div>
            <div>
                <h3>Reviews</h3>
            </div>
        </div>
        <div>
            <div>
                <?php
                print('<h1>' . $avgRating . '</h1>');
                print('<p>van de 5 gemiddeld</p>');
                ?>
            </div>
            <div>
                <?php
                for ($i3 = 0; $i3 < 5; $i3++) {
                    if ($avgRating > 0) {
                        print('<i class="fas fa-star"></i>');
                    } else {
                        print('<i class="far fa-star"></i>');
                    }
                    $avgRating--;
                }
                ?>
            </div>
            <div>
                <p>Aantal reviews: <?php print("<b>" . $itemCount . "</b>"); ?></p>
            </div>
        </div>
        <div>
            <form action="review.php" method="get">
                <input type="hidden" name="orderID" value="<?php print($itemID); ?>"/>
                <input class="buttonClass" type="submit" name="reviewButton" value="Schrijf een review">
            </form>
        </div>
        <div>
            <?php
            //                $sql1 = "SELECT COUNT(*) total FROM `review` WHERE StockItemID = ".$itemID;
            //                $result1 = $Connection->query($sql1);
            //                if ($result1->num_rows > 0) {
            ////                    $row1 = $result1->fetch_assoc();
            //                    print( "aantal: ".($result1->fetch_assoc()["total"])."<br>");
            //                }
            //                $result1 = $Connection->mysqli_fetch_array($sql1);
            //                print($result1['total']);

            //                $sql1=mysql_query("SELECT COUNT(*) AS total FROM review WHERE StockItemID = 136");
            //                $data=mysql_fetch_assoc($sql1);
            //                echo $data['total'];

            print("<br>");
            //                print($sql1);
            $sql2 = "SELECT PersonID, ReviewTitle, Rating, Recommend, Description, RateDate FROM review WHERE StockItemID = " . $itemID . " ORDER BY Rating DESC";
            $result2 = $Connection->query($sql2);
            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    print("<hr style='width: 50%; margin-left: 0;'><br><div class='individualReview'>");
                    $rating = $row2["Rating"];
                    for ($i3 = 0; $i3 < 5; $i3++) {
                        if ($rating > 0) {
                            print('<i class="fas fa-star"></i>');
                        } else {
                            print('<i class="far fa-star"></i>');
                        }
                        $rating--;
                    }

                    print("&#8287" . $row2["ReviewTitle"] . "<br>");
                    // Voeg bijvehorende gegevens van de klant toe aan de array uit customers tabel
                    $sql3 = "SELECT PreferredName FROM People WHERE PersonID = " . $row2["PersonID"];
                    $result3 = $Connection->query($sql3);
                    if ($result3->num_rows == 1) {
                        print($result3->fetch_assoc()["PreferredName"] . "&#8287;&#8287;&#8287;");
                        print($row2["RateDate"] . "<br>");
                    }
                    if ($row2["Recommend"]) {
                        print("<i class='fas fa-check-circle'></i>");
                        print(" Ik raad dit product aan<br>");
                    }
                    print("<br>" . $row2["Description"] . "<br>");
                    print("<br><hr>");
                    print("</div>");
                }
//                   
            } else {
                // De database is leeg, dus er staan geen accounts in
                print('<div></div>');
                print('<p>Er zijn nog geen reviews geschreven.</p>');
                print('</div>');
            }
            ?>
        </div>
    </div>
    <?php
} else {
    ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
} ?>

<?php
include __DIR__ . "/footer.php";
?>