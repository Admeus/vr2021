<?php
require("../../../../conf.php");
require("fnc_global.php");
require("fnc_users.php");

$notice = null;
$name = null;
$surname = null;
$email = null;
$gender = null;
$birthMonth = null;
$birthYear = null;
$birthDay = null;
$birthDate = null;
$monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];

//muutujad võimalike veateadetega
$nameError = null;
$surnameError = null;
$birthMonthError = null;
$birthYearError = null;
$birthDayError = null;
$birthDateError = null;
$genderError = null;
$emailError = null;
$passwordError = null;
$confirmpasswordError = null;

//kui on uue kasutaja loomise nuppu vajutatud
if (isset($_POST["submitUserData"])) {
    //kui on sisestatud nimi
    if (isset($_POST["firstName"]) and !empty($_POST["firstName"])) {
        $name = test_input($_POST["firstName"]);
    } else {
        $nameError = "Palun sisestage eesnimi!";
    } //eesnime kontrolli lõpp

    if (isset($_POST["surName"]) and !empty($_POST["surName"])) {
        $surname = test_input($_POST["surName"]);
    } else {
        $surnameError = "Palun sisesta perekonnanimi!";
    }

    if (isset($_POST["gender"])) {
        $gender = intval($_POST["gender"]);
    } else {
        $genderError = "Palun märgi sugu!";
    }

    //kontrollime, kas sünniaeg sisestati ja kas on korrektne
    if (isset($_POST["birthDay"]) and !empty($_POST["birthDay"])) {
        $birthDay = intval($_POST["birthDay"]);
    } else {
        $birthDayError = "Palun vali sünnikuupäev!";
    }

    if (isset($_POST["birthMonth"]) and !empty($_POST["birthMonth"])) {
        $birthMonth = intval($_POST["birthMonth"]);
    } else {
        $birthMonthError = "Palun vali sünnikuu!";
    }

    if (isset($_POST["birthYear"]) and !empty($_POST["birthYear"])) {
        $birthYear = intval($_POST["birthYear"]);
    } else {
        $birthYearError = "Palun vali sünniaasta!";
    }

    //vaja ka kuupäeva valiidsust kontrollida ja kuupäev kokku panna
    if (empty($birthMonthError) and empty($birthYearError) and empty($birthDayError)) {
        if (checkdate($birthMonth, $birthDay, $birthYear) === true) {
            $tempDate = new DateTime($birthYear . "-" . $birthMonth . "-" . $birthDay);
            $birthDate = $tempDate->format('Y-m-d');
        } else {
            $birthDateError = "Valitud kuupäev on vigane!";
        }
    }


    //email ehk kasutajatunnus

    if (isset($_POST["email"]) and !empty($_POST["email"])) {
        $email = test_input($_POST["email"]);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            $emailError = "Palun sisesta korrektne e-postiaadress!";
        }
    } else {
        $emailError = "Palun sisesta e-postiaadress!";
    }

    //parool ja selle kaks korda sisestamine

    if (!isset($_POST["password"]) or empty($_POST["password"])) {
        $passwordError = "Palun sisesta salasõna!";
    } else {
        if (strlen($_POST["password"]) < 8) {
            $passwordError = "Liiga lühike salasõna (sisestasite ainult " . strlen($_POST["password"]) . " märki).";
        }
    }

    if (!isset($_POST["confirmpassword"]) or empty($_POST["confirmpassword"])) {
        $confirmpasswordError = "Palun sisestage salasõna kaks korda!";
    } else {
        if ($_POST["confirmpassword"] != $_POST["password"]) {
            $confirmpasswordError = "Sisestatud salasõnad ei olnud ühesugused!";
        }
    }


    //Kui kõik on korras, salvestame
    if (empty($nameError) and empty($surnameError) and empty($birthMonthError) and empty($birthYearError) and empty($birthDayError) and empty($birthDateError) and empty($genderError) and empty($emailError) and empty($passwordError) and empty($confirmpasswordError)) {
        $notice = signUp($name, $surname, $email, $gender, $birthDate, $_POST["password"]);
        if ($notice == "ok") {
            $notice = "Uus kasutaja on loodud!";
            $name = null;
            $surname = null;
            $email = null;
            $gender = null;
            $birthMonth = null;
            $birthYear = null;
            $birthDay = null;
            $birthDate = null;
        } else {
            $notice = "Uue kasutaja salvestamisel tekkis tehniline tõrge: " . $notice;
        }
    } //kui kõik korras

} //kui on nuppu vajutatud

?>
<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veebirakendused ja nende loomine 2021</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body style="background-color:orange;">
    
<div class="login d-flex align-items-center py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
                <h3 class="login-heading mb-4">Registreeri</h3>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                    <div class="form-label-group">
                        <input name="firstName" value="<?php echo $name; ?>" type="text" id="inputFirstName" class="form-control" placeholder="First Name" required autofocus>
                        <span class="error-message"><?php echo $nameError; ?></span>
                        <label for="inputFirstName">Eesnimi</label>
                    </div>
                    <div class="form-label-group">
                        <input name="surName" value="<?php echo $surname; ?>" type="text" id="inputLastName" class="form-control" placeholder="Last Name" required>
                        <span class="error-message"><?php echo $surnameError; ?></span>
                        <label for="inputLastName">Perekonnanimi</label>
                    </div>
                    <div class="form-check form-check-inline signupradio">
                        <input id="inlineRadioFemale" class="form-check-input" type="radio" name="gender" value="2" <?php if ($gender == "2") {
                                                                                                                        echo " checked";
                                                                                                                    } ?>>
                        <label class="form-check-label" for="inlineRadioFemale">Naine</label>
                    </div>
                    <div class="form-check form-check-inline signupradio">
                        <input id="inlineRadioMale" class="form-check-input" type="radio" name="gender" value="1" <?php if ($gender == "1") {
                                                                                                                        echo " checked";
                                                                                                                    } ?>>
                        <label class="form-check-label" for="inlineRadioFemale">Mees</label>

                    </div>
                    <div class="form-row">
                        <span class="error-message"><?php echo $genderError; ?></span>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <select id="inputBirthday" name="birthDay" class="form-control">
                                <?php
                                echo "\t \t" . '<option value="" selected disabled>Sünnipäev</option>' . "\n";
                                for ($i = 1; $i < 32; $i++) {
                                    echo "\t \t" . '<option value="' . $i . '"';
                                    if ($i == $birthDay) {
                                        echo " selected";
                                    }
                                    echo ">" . $i . "</option> \n";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <select id="inputBirthMonth" name="birthMonth" class="form-control">
                                <?php
                                echo "\t \t" . '<option value="" selected disabled>Sünnikuu</option>' . "\n";
                                for ($i = 1; $i < 13; $i++) {
                                    echo "\t \t" . '<option value="' . $i . '"';
                                    if ($i == $birthMonth) {
                                        echo " selected ";
                                    }
                                    echo ">" . $monthNamesET[$i - 1] . "</option> \n";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <select id="inputbirthYear" name="birthYear" class="form-control">
                                <?php
                                echo "\t \t" . '<option value="" selected disabled>Sünniaasta</option>' . "\n";
                                for ($i = date("Y") - 15; $i >= date("Y") - 110; $i--) {
                                    echo "\t \t" . '<option value="' . $i . '"';
                                    if ($i == $birthYear) {
                                        echo " selected ";
                                    }
                                    echo ">" . $i . "</option> \n";
                                }
                                ?>
                            </select>
                        </div>
                        <span class="error-message"><?php echo $birthDateError . " " . $birthDayError . " " . $birthMonthError . " " . $birthYearError; ?></span>
                    </div>
                    <div class="form-label-group">
                        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" value="<?php echo $email; ?>" required>
                        <span class="error-message"><?php echo $emailError; ?></span>
                        <label for="inputEmail">E-posti aadress</label>
                    </div>
                    <div class="form-label-group">
                        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                        <span><?php echo $confirmpasswordError; ?></span>
                        <label for="inputPassword">Salasõna (min 8 tähemärki)</label>
                    </div>
                    <div class="form-label-group">
                        <input name="confirmpassword" type="password" id="inputConfirmPassword" class="form-control" placeholder="Password" required>
                        <span><?php echo $confirmpasswordError; ?></span>
                        <label for="inputConfirmPassword">Korrake salasõna</label>
                    </div>
                    <button name="submitUserData" class="btn btn-lg btn-secondary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Registreeri</button>
                    <span><?php echo $notice; ?></span>
                    <a class="d-block text-center mt-2 small" href="index.php">Logi Sisse</a>
                </form>
            </div>
        </div>
    </div>
</div>

    


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>



