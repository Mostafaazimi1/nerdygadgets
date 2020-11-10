<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <style>
        * {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "segoe ui", roboto, oxygen, ubuntu, cantarell, "fira sans", "droid sans", "helvetica neue", Arial, sans-serif;
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .login {
            width: 400px;
            background-color: #ffffff;
            box-shadow: 0 0 9px 0 rgba(0, 0, 0, 0.3);
            margin: 100px auto;
        }
        .login h1 {
            text-align: center;
            color: #5b6574;
            font-size: 24px;
            padding: 20px 0 20px 0;
            border-bottom: 1px solid #dee0e4;
        }
        .login form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding-top: 20px;
        }
        .login form label {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
            background-color: #3274d6;
            color: #ffffff;
        }
        .login form input[type="password"], .login form input[type="email"] {
            width: 310px;
            height: 50px;
            border: 1px solid #dee0e4;
            margin-bottom: 20px;
            padding: 0 15px;
        }
        .login form input[type="submit"] {
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background-color: #3274d6;
            border: 0;
            cursor: pointer;
            font-weight: bold;
            color: #ffffff;
            transition: background-color 0.2s;
        }
        .login form input[type="submit"]:hover {
            background-color: #2868c7;
            transition: background-color 0.2s;
        }
    </style>
</head>
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
            <input type="submit" name="loginSend" class="button" value="Inloggen">
        </form>
    </div>
    <div>
        <?php
        /**
         * checks if login form has been send so yes than function loginPass is activated
         */

        if (isset($_POST["sendLogin"])) {
            logInPass();
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