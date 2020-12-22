<?php
include __DIR__ . "/header.php";
if(isset($_SESSION['login']) && isset($_SESSION['messageCount'])) {
    if(($_SESSION['messageCount'] == 1)) {
        $_SESSION['messageCount'] = 0;
        print("<h4>U bent successvol ingelogd.</h4>");
        mysqli_close($Connection);
        exit();
    }
}
if(isset($_SESSION['messageCount2'])) {
    unset($_SESSION['messageCount2']);
    print("<h4>Uw order is successvol geplaatst</h4>");
}
if(isset($_SESSION['messageCount3'])) {
    if($_SESSION['messageCount3'] != 0) {
        print("<h4>Uw account is successvol aangemaakt!</h4>");
        $_SESSION['messageCount3']--;
    } else {
        print("<h4>Uw account is successvol aangemaakt!</h4>");
        unset($_SESSION['messageCount3']);
    }
}
?>
<div class="naastElkaar SingleProductIndex">
    <div class="ProductIndexInfo">
        <h1><span style="color: #007bff">"The Gu"</span> red shirt XML tag t-shirt (Black) M</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <h4>€ 30.95</h4>
        <a class="ButtonIndex" href="view.php?id=93">Meer informatie</a>
    </div>
    <div class="ProductIndexImage">
        <img src="Public/ProductIMGHighRes/580b57fbd9996e24bc43bf55.png">
    </div>
</div>
<div class="naastElkaar IndexCategorie">
    <h3 class="H3Index">Bladeren door onze categorieën</h3>
    <div class="CategorieCon">
        <img src="Public/StockGroupIMG/Chocolate.jpg">
        <a class="CategorieButton" href="browse.php?category_id=1">Novelty Items</a>
    </div>
    <div class="CategorieCon">
        <img src="Public/StockGroupIMG/Clothing.jpg">
        <a class="CategorieButton" href="browse.php?category_id=2">Clothing</a>
    </div>
    <div class="CategorieCon">
        <img src="Public/StockGroupIMG/T-shirts.jpg">
        <a class="CategorieButton" href="browse.php?category_id=4">T-shirt</a>
    </div>
    <div class="CategorieCon">
        <img src="Public/StockGroupIMG/ComputingNovelties.jpg">
        <a class="CategorieButton" href="browse.php?category_id=6">Computing Novelties</a>
    </div>
    <div class="CategorieCon">
        <img src="Public/StockGroupIMG/USBNovelties.jpg">
        <a class="CategorieButton" href="browse.php?category_id=7">USB Novelties</a>
    </div>
    <div class="CategorieCon">
        <img src="Public/StockGroupIMG/Toys.jpg">
        <a class="CategorieButton" href="browse.php?category_id=9">Toys</a>
    </div>

</div>
<?php
include __DIR__ . "/footer.php";
?>
