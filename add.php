<?php
include "functions.php";

if (isset($_POST["action"])) {
    session_start();

    $productID = $_POST["addcart"];
    $aantal = $_POST["aantal"];

    $total = addItem($productID, $aantal);

    //header("Location: view.php?id=" . $productID . "&amount=". $total ."&succes=true");
    print("
        <script>
            alert('Product added!');window.location='view.php?id=$productID&amount=$total&succes=true'
        </script>
         ");
    die();
}

