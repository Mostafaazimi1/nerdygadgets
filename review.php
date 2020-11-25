<?php
include __DIR__ . "/header.php";
if (isset($_GET["orderID"])) {
    print("<div>Terug</div>");
}
if(isset($_GET["reviewButton"])) {
    if (isset($_GET["orderID"])) {
        print("joa");
//        Schrijf je review
    }
} else {
    print('<div class="notificationError">');
    print('<h2>Sorry, we kunnen deze pagina niet meer vinden</h2><br>');
    print('<p>Het lijkt erop dat deze pagina niet (meer) bestaat of misschien verhuisd is.</p>');
    print('<a href="./"<input id="writeReview" type="submit" name="reviewButton" value="Schrijf een review">');
    print('</div>');
}
?>