<?php
include __DIR__ . "/header.php";
if(isset($_SESSION['login']) && isset($_SESSION['messageCount'])) {
    if(($_SESSION['messageCount'] == 1)) {
        $_SESSION['messageCount'] = 0;
        print("<h4>U bent successvol ingelogd.</h4>");
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
<div class="IndexStyle">
    <div class="col-11">
        <div class="TextPrice">
            <a href="view.php?id=93">
                <div class="TextMain">
                    "The Gu" red shirt XML tag t-shirt (Black) M
                </div>
<!--            <ul id="ul-class-price">-->
                <ul>
                    <li class="HomePagePrice">€30.95</li>
                </ul>

        </div>
        </a>
        <div class="HomePageStockItemPicture"></div>
    </div>
</div>
<?php
include __DIR__ . "/footer.php";
?>
