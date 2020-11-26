<?php
include __DIR__ . "/header.php";
if(!isset($_SESSION['login'])){
    $_SESSION['reviewID'] = $_GET['orderID'];
    print('<meta http-equiv = "refresh" content = "0; url = ./login.php" />');
    } else {
        if(isset($_POST["submitReview"])) {
            $gegevens = $_SESSION['login'];

            $sql1 = "INSERT INTO Review (PersonID, StockItemID, ReviewTitle, Rating, Recommend, Description)
                    VALUES(".$gegevens["PersonID"].", ".$_POST['orderID'].", '".$_POST['title']."', "
                    .$_POST['star'].", ".$_POST['recommend'].", '".trim($_POST['description'])."')";
            $result1 = $Connection->query($sql1);
            print($result1);
//            if ($result1->num_rows > 0) {
//                while ($row1 = $result1->fetch_assoc()) {
//                    // Voeg bijvehorende gegevens van de klant toe aan de array uit customers tabel
//                    $itemCount = $row1["total"];
//                    $avgRating = (int)$row1["avgrating"];
//                    continue;
//                }
//            }
//            print($gegevens["PersonID"]);
//            print("<br>");
//            print($_POST['orderID']);
//            print("<br>");
//            print($_POST['title']);
//            print("<br>");
//            print($_POST['star']);
//            print("<br>");
//            print($_POST['recommend']);
//            print("<br>");
//            print($_POST['description']);

        } else {
            if (isset($_GET["orderID"]) OR isset($_GET['newLogin'])) {
                if(isset($_GET["orderID"])){
                    $orderID = $_GET["orderID"];
                } elseif(isset($_GET['newLogin'])) {
                    $orderID = $_GET['newLogin'];
                }
                print('<div>');
                print('<a class="buttonClass" href="./view.php?id='.$orderID.'">Terug</a>');
                print('&#160;&#160;<a class="buttonClass" href="./">Home pagina</a>');
                print('</div><br>');

                if(isset($_GET["reviewButton"])) {
                    if (isset($orderID)) {
                        //Schrijf je review
                        if(isset($_SESSION['login'])){
                            $gegevens = $_SESSION['login'];
                        }
                    }
                } else {
                    print('<div class="notificationError">');
                    print('<h2>Sorry, we kunnen deze pagina niet meer vinden</h2><br>');
                    print('<p>Het lijkt erop dat deze pagina niet (meer) bestaat of misschien verhuisd is.</p>');
                    print('</div>');
                }
            } else {
                print('<div><a class="buttonClass" href="./">Home pagina</a></div><br>');
                print('<div class="notificationError">');
                print('<h2>Sorry, we kunnen deze pagina niet meer vinden</h2><br>');
                print('<p>Het lijkt erop dat deze pagina niet (meer) bestaat of misschien verhuisd is.</p>');
                print('</div>');
            }
?>
<style>

</style>

<div id="reviews">
    <div>
        <h1>Schrijf je review</h1><br><br>
    </div>
    <div>
        <form action="./review.php" method="post">
            <div id="ratingstars" class="ratingstars">
                <p>Aantal sterren *</p>
                <input type="radio" id="star5" name="star" value="5" class="radio-btn starhide" required/>
                <label for="star5" >☆</label>
                <input type="radio" id="star4" name="star" value="4" class="radio-btn starhide" />
                <label for="star4" >☆</label>
                <input type="radio" id="star3" name="star" value="3" class="radio-btn starhide" />
                <label for="star3" >☆</label>
                <input type="radio" id="star2" name="star" value="2" class="radio-btn starhide" />
                <label for="star2" >☆</label>
                <input type="radio" id="star1" name="star" value="1" class="radio-btn starhide" />
                <label for="star1" >☆</label>
            </div><br>
            <div id="recommend">
                <p>Zou je het artikel aanbevelen? *</p>
                <label for="yes">Ja</label>
                <input type="radio" id="yes" name="recommend" value="1" checked required>
                <label for="no">Nee</label>
                <input type="radio" id="no" name="recommend" value="0"><br><br>
            </div>
            <div>
                <label for="title">
                    Geef je review een titel *
                </label>
                <input type="text" name="title" id="title" required><br><br>
            </div>
            <div>
                <label for="description">
                    Wat vind je van het artikel? *
                </label>
                <textarea class="text-input" id="description" name="description" rows="9" cols="70" placeholder="Beschrijf je ervaringen met het artikel ..." maxlength="4000" required></textarea>
            </div>
            <div>
                <input type="hidden" name="orderID" value="<?php print($_GET['orderID']); ?>" />
                <input type="submit" name="submitReview" class="button" value="Review plaatsen">
            </div>
        </form>
    </div>
</div>
<?php }} ?>
