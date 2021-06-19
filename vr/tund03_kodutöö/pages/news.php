<?php

// kas on sisseloginud
if (!isset($_SESSION["userid"])) {
    //Jıuga avalehele
    header("Location: ../index.php");
}

require("fnc_news.php");

$newsHTML = readNews(1);

if (isset($_POST["newsDelBtn"])) {
    deleteNews($_POST["newsDelBtn"]);
    $newsHTML = readNews($_POST["limitSet"]);
}

if (isset($_POST["limitSet"])) {

    $newsHTML = readNews($_POST["limitSet"]);
}

?>

<div class="container" style="max-width: 60%; margin-top:100px;">

    <section class="text-center">
        <h1 class="jumbotron-heading">Uudised</h1>
        <p class="lead text-muted">Siit saab lugeda k√µiki uudiseid!</p>
        <br>
    </section>

    <div>
        <section class="text-center">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . $currentpage; ?>">
                <label for="limit"></label>
                <?php
                $aDropd = array("1", "5", "10", "100");
                echo '<div class="form-group col-md-4">
                    <label for="limit">Vali, mitu uudist soovid kuvada:</label>  
                    <select id="limit" class="form-control" onchange="this.form.submit();" name="limitSet">';
                foreach ($aDropd as $sOption) {
                    $sSel = ($sOption == $_POST['limitSet']) ? "Selected='selected'" : "";
                    echo "<option   $sSel>$sOption</option>";
                }
                echo '</select></div>';
                echo '<div>';
                echo $newsHTML;
                echo '</div>';
                ?>
            </form>
        </section>
    </div>
</div>