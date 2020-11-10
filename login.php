<?php
session_start();
include __DIR__ . "/header.php";

if(isset($_SESSION["PersonID"])) {
    print("<h1>U bent al ingelogd!</h1>");
} else {
    ?>
    <div class="login">
        <h1>Inloggen</h1>
        <form action="login.php" method="post">
            <label for="email">
                <i class="fas fa-user"></i>
            </label>
            <input type="email" name="email" placeholder="E-mailadres*" id="email" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Wachtwoord*" required>
            <input type="submit" name="submitLogin" class="button" value="Inloggen">
        </form>
    </div>
    <div>
        <?php
        /**
         * checks if login form has been send so yes than function loginPass is activated
         */

        if (isset($_POST["submitLogin"])) {
            //logInPass();
            print("hellp");
        }
        ?>
    </div>
    <?php
}
?>
<?php
/**
 * Checks if person is allready logged in
 * if Y than warning that the person in already logged in
 * If N than the 2 forms are being outputted
 */
//
//if (isset($_SESSION["PersonID"])) {
//    ?><!-- <h1>U bent al ingelogd!</h1>--><?php
//} else {
//    ?>
<!--    <!--    Form voor inloggen-->-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="login">-->
<!--                <div class="col-md-4">-->
<!--                    <h1>Log in</h1>-->
<!--                    <form action="login.php" method="post">-->
<!--                        <input type="email" name="emailLog" placeholder="E-mailadres*" required>-->
<!--                        <input type="password" name="passw" placeholder="Wachtwoord*" required>-->
<!--                        <input type="submit" name="sendLogin" class="button" value="Log in">-->
<!--                    </form>-->
<!--                    <div >-->
<!--                        --><?php
//                        /**
//                         * checks if login form has been send so yes than function loginPass is activated
//                         */
//
//                        if (isset($_POST["sendLogin"])) {
//                            logInPass();
//                        }
//                        ?>
<!--                    </div>-->
<!--                </div>-->
<!--                <!-- Form for creating account by first sending the email-->-->
<!--                <div class="col-md-4 col-md-push-2">-->
<!--                    <h1>Maak een account</h1>-->
<!--                    <p>Nieuw bij Wide World Importers?</p>-->
<!--                    <p>Maak een account aan!</p>-->
<!--                    <form action="login.php" method="post">-->
<!--                        <input type="email" name="EmailRegister" placeholder="Vul uw E-mailadres in*" required>-->
<!--                        <input type="submit" name="sendEmail" class="button" value="Maak een account aan">-->
<!--                    </form>-->
<!--                    --><?php
//                    if (isset($_POST["sendEmail"])) {
//                        getEmail($_POST["EmailRegister"]);
//                    }
//                    ?>
<!--                </div>-->
<!--            </div>-->
<!---->
<!--        </div>-->
<!--    </div>-->
<!--    --><?php
//
//    /**
//     * checks if person has just created an account gets data from register function
//     */
//    if (isset($_SESSION["account"])) {
//        if ($_SESSION["account"]) {
//            ?>
<!--            <div class="container">-->
<!--                <div class="row">-->
<!--                    <div class="col-md-12">-->
<!--                        --><?php
//                        print("<p>Account succesvol aangemaakt</p>");
//                        unset($_SESSION["account"]);
//                        ?>
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            --><?php
//
//        }
//    }
//}