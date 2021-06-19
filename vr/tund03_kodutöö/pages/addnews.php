<?php

// kas on sisseloginud
if (!isset($_SESSION["userid"])) {
    //Jõuga avalehele
    header("Location: ../index.php");
}

require("fnc_news.php");


$newsTitle  = null;
$newsContent = null;
$newsError = null;

if (isset($_POST["newsBtn"])) {

    if (isset($_POST["newsTitle"]) and !empty(test_input($_POST["newsTitle"]))) {
        $newsTitle = test_input($_POST["newsTitle"]);
    } else {
        $newsError = "Uudise pealkiri on sisestamata! ";
    }

    if (isset($_POST["newsEditor"]) and !empty(test_input($_POST["newsEditor"]))) {
        $newsContent = test_input($_POST["newsEditor"]);
    } else {
        $newsError .= "Uudise sisu on kirjutamata!";
    }

    //Saadame andmebaasi
    if (empty($newsError)) {
        //echo "Salvestame";
        $response = saveNews($newsTitle, $newsContent);

        if ($response == 1) {
            $newsError = "Uudis on salvestatud!";
        } else {
            $newsError = "Uudise salvestamisel tekkis tõrge!";
        }
    }
}

?>

<div class="container" style="max-width: 60%; margin-top:100px;">

    <section class="text-center">
        <h1 class="jumbotron-heading">Uudise lisamine</h1>
        <p class="lead text-muted">Sellel lehel on võimalik lisada uudiseid!</p>
        <br>
    </section>


    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . $currentpage; ?>">
        <label>Uudise pealkiri:</label>
        <br>
        <input type="text" class="form-control" name="newsTitle" placeholder="Uudise pealkiri" value="<?php echo $newsTitle; ?>"><br>
        <br>
        <label>Uudise sisu:</label><br>
        <textarea class="form-control" name="newsEditor" placeholder="Rahvatants on emotsioon" rows="6" cols="40"><?php echo $newsContent; ?></textarea><br>
        <br>
        <input type="submit" class="btn btn-secondary" name="newsBtn" value="Salvesta uudis!">
        <span><?php echo $newsError; ?></span>
    </form>

</div>