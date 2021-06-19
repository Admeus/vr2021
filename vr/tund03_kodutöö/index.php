<?php
require("../../../../configuration.php");
require("fnc_global.php");
require("fnc_users.php");

require("classes/Session.class.php");

SessionManager::sessionStart("vr20", 0, "/~ainar.kiison/", "tigu.hk.tlu.ee");

$notice = null;
$email = null;
$emailError = null;
$passwordError = null;


if (isset($_POST["login"])) {
    if (isset($_POST["email"]) and !empty($_POST["email"])) {
        $email = test_input($_POST["email"]);
    } else {
        $emailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
    }

    if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8) {
        $passwordError = "Palun sisesta parool, vähemalt 8 märki!";
    }

    if (empty($emailError) and empty($passwordError)) {
        $notice = signIn($email, $_POST["password"]);
    } else {
        $notice = "Ei saa sisse logida!";
    }
}
?>

<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veebirakendused ja nende loomine 2021</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body style="background-color:orange;">
<div class="login d-flex align-items-center py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
                <h3 class="login-heading mb-4">Tere tulemast!</h3>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-label-group">
                        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" value="<?php echo $email; ?>" autofocus>
                        <span class="error-message"><?php echo $emailError; ?></span>
                        <label for="inputEmail">E-mail</label>
                    </div>

                    <div class="form-label-group">
                        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password">
                        <span class="error-message"><?php echo $passwordError; ?></span>
                        <label for="inputPassword">Salasõna</label>
                    </div>

                    <button name="login" class="btn btn-lg btn-secondary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Logi Sisse</button>
                    <span class="error-message"><?php echo $notice; ?></span>
                    <div class="text-center">
                        <a class="small" href="newuser.php">Registreeri!</a>
                    </div>
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