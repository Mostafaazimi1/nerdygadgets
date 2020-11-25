<?php
$Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
mysqli_set_charset($Connection, 'latin1');
include __DIR__ . "/header.php";

$Query = " 
           SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
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

$sql1 = "SELECT COUNT(*) AS total, AVG(rating) AS avgrating FROM `review` WHERE StockItemID = ".$itemID;
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
        <?php
        if (isset($Result['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $Result['Video']; ?>
            </div>
        <?php }
        ?>

        <h1 class="StockItemNameViewSize StockItemName"><?php print $Result['StockItemName'];?></h1>
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
                        <div class="VoorraadText"><?php if (isset($Result['QuantityOnHand']) >= 1000){ echo "<p class='voorraad'><i class='fas fa-box' style='color:#2BAE49; padding-right: 7px;' aria-hidden='true'></i>Ruime voorraad beschikbaar.</p>";
                        }
                        else { print ($Result['QuantityOnHand']); }?></div><br>
                        <form action="add.php" method="post">
                            <input type="hidden" name="action" value="submit" />
                            Aantal<br><input type="number" name="aantal" min="0" value="1" max="<?php echo $Result['aantal']; ?>" style="margin-bottom: 12px;">
                            <button class="bestelling-btn" type="submit" name="addcart" value="<?php print $Result['StockItemID']?>"><i class="fas fa-shopping-cart" style="color:#FFFFFF; padding-right: 7px;" aria-hidden="true"></i>Toevoegen aan winkelwagen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="ExtraInfoView naastElkaar">
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
        <div class="reviews">
            <div>
                <div>
                    <h3>Reviews</h3>
                </div>
            </div>
            <div>
                <div>
                    <?php
                        print('<h1>'.$avgRating.'</h1>');
                        print('<p>van de 5 gemiddeld</p>');
                    ?>
                </div>
                <div>
                    <?php
                        for($i3=0; $i3 < 5; $i3++) {
                            if($avgRating > 0) {
                                print('<i class="fas fa-star"></i>');
                            } else {
                                print('<i class="far fa-star"></i>');
                            }
                            $avgRating--;
                        }
                    ?>
                </div>
                <div>
                    <p>Aantal reviews: <?php print("<b>".$itemCount."</b>");?></p>
                </div>
            </div>
            <div>
                <form action="review.php" method="get">
                    <input type="hidden" name="orderID" value="<?php print($itemID); ?>" />
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
                $sql2 = "SELECT PersonID, ReviewTitle, Rating, Recommend, Description, RateDate FROM review WHERE StockItemID = ".$itemID;
                $result2 = $Connection->query($sql2);
                if ($result2->num_rows > 0) {
                    while ($row2 = $result2->fetch_assoc()) {
                        print("<div class='individualReview'>");
                        $rating = $row2["Rating"];
                        for($i3=0; $i3 < 5; $i3++) {
                            if($rating > 0) {
                                print('<i class="fas fa-star"></i>');
                            } else {
                                print('<i class="far fa-star"></i>');
                            }
                            $rating--;
                        }
                        print("&#8287".$row2["ReviewTitle"]."<br>");
                        // Voeg bijvehorende gegevens van de klant toe aan de array uit customers tabel
                        $sql3 = "SELECT PreferredName FROM People WHERE PersonID = ".$row2["PersonID"];
                        $result3 = $Connection->query($sql3);
                        if ($result3->num_rows == 1) {
                            print($result3->fetch_assoc()["PreferredName"]."&#8287;&#8287;&#8287;");
                            print($row2["RateDate"]."<br>");
                        }
                        if($row2["Recommend"]) {
                            print("<i class='fas fa-check-circle'></i>");
                            print(" Ik raad dit product aan<br>");
                        }
                        print("<br>".$row2["Description"]."<br>");
                        print("<br><br>");
                        print("</div>");
                        continue;
                    }
//                    // output data of each row
//                    $passFound = FALSE;
//                    while ($row = $result->fetch_assoc()) {
//                        if (($row["LogonName"] != "NO LOGON") && ($row["LogonName"] == strtolower($_POST["email"])) && ($_POST["password"] == $row["HashedPassword"])) {
//                            if ($row["CustomerNUM"] == "") {
//                                // Er is geen customerid gekoppeld, dus de opgeslagen account gegevens zijn incompleet.
//                                // De gebruiker krijgt hievan een melding
//                                unset($passFound);
//                                $email = 'value="' . $_POST["email"] . '"';
//                                $password = 'value="' . $_POST["password"] . '"';
//                                print('<div class="notificationError">');
//                                print('<h2>We wijzen je graag op het volgende:</h2><br>');
//                                print('<p>Uw accountgegevens zijn beschadigd. Neem alstublieft contact op met de systeembeheerder.</p>');
//                                print('</div>');
//                            } elseif (($row["IsPermittedToLogon"] == 0)) {
//                                // De gebruiker mag niet mag inloggen, hij krijgt hievan een melding
//                                unset($passFound);
//                                $email = 'value="' . $_POST["email"] . '"';
//                                $password = 'value="' . $_POST["password"] . '"';
//                                print('<div class="notificationError">');
//                                print('<h2>We wijzen je graag op het volgende:</h2><br>');
//                                print('<p>Uw account is uitgeschakeld door de systeembeheerder.</p>');
//                                print('</div>');
//                                //registratiemelding geven
//                            } elseif ($row["CustomerNUM"] != "") {
//                                // De gegevens komen overeen, de gebruiker mag en wordt ingelogd
//                                // Array met de gegevens van de klant, geplaatst in $_SESSION['login'] = $loginData;
//                                $_SESSION['messageCount'] = 1;
//                                $passFound = TRUE;
//                                $loginData = array(
//                                    "PersonID" => $row["PersonID"],
//                                    "FullName" => $row["FullName"],
//                                    "PreferredName" => $row["PreferredName"],
//                                    "IsPermittedToLogon" => $row["IsPermittedToLogon"],
//                                    "LogonName" => $row["LogonName"],
//                                    "PhoneNumber" => $row["PhoneNumber"],
//                                    "EmailAddress" => $row["EmailAddress"],
//                                );
//                                $sql3 = "SELECT CustomerID, CustomerName, DeliveryMethodID, DeliveryCityID,
//                PostalCityID, PhoneNumber, DeliveryAddressLine2, DeliveryPostalCode,
//                PostalPostalCode, PostalAddressLine2 FROM Customers WHERE CustomerID = " . $row["CustomerNUM"] . ";";
//                                $result3 = $Connection->query($sql3);
//                                if ($result3->num_rows > 0) {
//                                    while ($row2 = $result2->fetch_assoc()) {
//                                        // Voeg bijvehorende gegevens van de klant toe aan de array uit customers tabel
//                                        $loginData["CustomerID"] = $row2["CustomerID"];
//                                        $loginData["CustomerName"] = $row2["CustomerName"];
//                                        $loginData["DeliveryMethodID"] = $row2["DeliveryMethodID"];
//                                        $loginData["DeliveryCityID"] = $row2["DeliveryCityID"];
//                                        $loginData["PostalCityID"] = $row2["PostalCityID"];
//                                        $loginData["PhoneNumber2"] = $row2["PhoneNumber"];
//                                        $loginData["DeliveryAddressLine2"] = $row2["DeliveryAddressLine2"];
//                                        $loginData["DeliveryPostalCode"] = $row2["DeliveryPostalCode"];
//                                        $loginData["PostalPostalCode"] = $row2["PostalPostalCode"];
//                                        $loginData["PostalAddressLine2"] = $row2["PostalAddressLine2"];
//                                        continue;
//                                    }
//                                }
//                                $_SESSION['login'] = $loginData;
//                                continue;
//                            } else {
//                                print("error! No connection with customer table");
//                            }
//                        }
//                    }
//                    if (isset($passFound)) {
//                        // Wachtwoord is gevonden, gebruiker wordt geredirect naar home
//                        if ($passFound) {
//                            print('<meta http-equiv = "refresh" content = "0; url = ./" />');
//                            exit();
//                        } else {
//                            // Wachtwoord is niet gevonden, gebruiker moet opnieuw invoeren
//                            print('<div class="notificationError">');
//                            print('<h2>We wijzen je graag op het volgende:</h2><br>');
//                            print('<p>De combinatie van e-mailadres en wachtwoord is niet geldig.</p>');
//                            print('</div>');
//                            $email = 'value="' . $_POST["email"] . '"';
//                            $password = 'value="' . $_POST["password"] . '"';
//                            //registratiemelding geven
//                        }
//                    }
                } else {
                    // De database is leeg, dus er staan geen accounts in
                    print('<div class="notificationError">');
                    print('<h2>We wijzen je graag op het volgende:</h2><br>');
                    print('<p>De combinatie van e-mailadres en wachtwoord is niet geldig.</p>');
                    print('</div>');
                    $email = 'value="' . $_POST["email"] . '"';
                    $password = 'value="' . $_POST["password"] . '"';
                    //registratie melding geven
                }
                ?>
            </div>
        </div>
        <?php
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>

