<?php session_start();
include __DIR__ . "/header.php"; ?>
<div class="winkelmandje">
    <h1>Winkelmandje</h1>
    <div class="overzicht-wrapper">
        <div class="product-overzicht">
            <table>
                <tr>
                    <th>Foto</th>
                    <th>Title</th>
                    <th>Prijs</th>
                    <th>Aantal</th>
                    <th>Subtotaal</th>
                    <th>Verwijderen</th>
                </tr>
                <tr>
                    <td><img></td>
                    <td><p>Test</p></td>
                    <td><p>€395</p></td>
                    <td><p>2</p></td>
                    <td><p>€790</p></td>
                    <td>X</td>
                </tr>
            </table>
        </div>
        <div class="prijs-overzicht">
            <h2>Prijs overizcht</h2>
            <table>
                <tr>
                    <td>Subtotaal</td>
                    <td class="td-geld table-rechts">€1066,-</td>
                </tr>
                <tr>
                    <td>Verzendkosten</td>
                    <td class="td-gratis-verz table-rechts">Gratis</td>
                </tr>
            </table>
            <hr class="betalen-hr">
            <table>
                <tr>
                    <td>Totaalprijs</td>
                    <td class="td-geld table-rechts">€1066,-</td>
                </tr>
            </table>
            <p>Inclusief btw</p>
            <button class="bestelling-btn">Bestelling afronden</button>
        </div>
    </div>


</div>
