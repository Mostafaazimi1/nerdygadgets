<?php
include "connect.php";
session_start();
if (!isset($_SESSION['winkelwagen'])) {
    $_SESSION['winkelwagen'] = array();
}
?>
<!DOCTYPE html>
<html lang="en" style="">
<head>
    <script src="Public/JS/fontawesome.js" crossorigin="anonymous"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/Resizer.js"></script>
    <script src="Public/JS/jquery-3.4.1.js"></script>
    <style>
        @font-face {
            font-family: MmrText;
            src: url(/Public/fonts/mmrtext.ttf);
        }
    </style>
    <meta charset="ISO-8859-1">
    <title>NerdyGadgets</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Public/CSS/Style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/nha3fuq.css">
    <link rel="apple-touch-icon" sizes="57x57" href="Public/Favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="Public/Favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="Public/Favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="Public/Favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="Public/Favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="Public/Favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="Public/Favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="Public/Favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="Public/Favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="Public/Favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Public/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="Public/Favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Public/Favicon/favicon-16x16.png">
    <link rel="manifest" href="Public/Favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="Public/Favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
<div class="centerContainer">
    <div class="row" id="Header">
        <div class="logoDiv">
            <a href="./">
                <img id="LogoImage" src="Public/ProductIMGHighRes/NerdyGadgets-Logo.png">
            </a>
        </div>
        <div class="menuDiv">
            <ul id="ul-class-topBar">
                <li>
                    <a href="browse.php" class="HrefDecoration"><i class="fas fa-search" style="color:#676EFF;"></i>
                        Zoeken</a>
                </li>
                <li>
                    <?php
                    if (isset($_SESSION['login'])) {
                        if ($_SESSION['login']) {
                            print('<a href="logout.php" class="HrefDecoration">Uitloggen</a>');
                        }
                    } else {
                        print('<a href="login.php" class="HrefDecoration">Inloggen</a>');
                    }
                    ?>
                </li>
                <li>
                    <a href="winkelmandje.php" class="HrefDecoration"><i class="fas fa-shopping-cart"
                                                                         style="color:#676EFF;"></i> Winkelmandje</a>
                </li>
            </ul>
        </div>
    </div class="row">
    <div class="row" id="HeaderCategorie">
        <div id="CategoriesBar">
            <ul id="ul-class-categorie">
                <?php
                $Query = "
                SELECT StockGroupID, StockGroupName, ImagePath
                FROM stockgroups 
                WHERE StockGroupID IN (
                                        SELECT StockGroupID 
                                        FROM stockitemstockgroups
                                        ) AND ImagePath IS NOT NULL
                ORDER BY StockGroupID ASC;
                ";
                $Statement = mysqli_prepare($Connection, $Query);
                mysqli_stmt_execute($Statement);
                $HeaderStockGroups = mysqli_stmt_get_result($Statement);

                foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                    <li>
                        <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                           class="HrefDecorationCategorie"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <a href="categories.php" class="HrefDecorationCategorie">Hele assortiment</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row" id="Content">
        <div class="col-12" id="geenMargin">
            <div id="SubContent">