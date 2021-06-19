<?php

// kas on sisseloginud
if (!isset($_SESSION["userid"])) {
    //Jõuga avalehele
    header("Location: ../index.php");
}

require("fnc_study.php");

$studyTopicsOptions = getStudyTopicsOptions();
$studyActivitiesOptions = getStudyActivitiesOptions();

$studyTopicId = null;
$studyActivity = null;
$elapsedTime = null;
$studyError = null;

if (isset($_POST['studyBtn'])) {

    if (isset($_POST["studyTopicId"]) and !empty(test_input($_POST["studyTopicId"]))) {
        $studyTopicId = test_input($_POST["studyTopicId"]);
    } else {
        $studyError .= "Õppeaine on valimata! ";
    }

    if (isset($_POST["studyActivity"]) and !empty(test_input($_POST["studyActivity"]))) {
        $studyActivity = test_input($_POST["studyActivity"]);
    } else {
        $studyError .= "Tegevus on valimata! ";
    }

    if (isset($_POST["elapsedTime"]) and !empty(test_input($_POST["elapsedTime"])) and $_POST["elapsedTime"] != 0) {
        $elapsedTime = test_input($_POST["elapsedTime"]);
    } else {
        $studyError .= "Tegevusele kulund aeg on määramata! ";
    }

    //Saadame andmebaasi
    if (empty($studyError)) {

        $response = saveStudy($studyTopicId, $studyActivity, $elapsedTime);

        if ($response == 1) {
            $studyError = "Tegevus on salvestatud";
        } else {
            $studyError = "Tegevuse salvestamisel tekkis tõrge!";
        }
    }
}

?>

<div class="container" style="max-width: 60%; margin-top:100px;">
    <section class="text-center">

        <section class="text-center">
            <h1 class="jumbotron-heading">Õppetegevuse sisestamine</h1>
            <p class="lead text-muted">Siin lehel on võimalik sisestada õppetegevust!</p>
            <br>
        </section>

        <div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . $currentpage; ?>">
                <div class="form-row">
                    <div class="col">
                        <select class="form-control" name="studyTopicId">
                            <option value="" selected disabled>Õppeaine</option>
                            <?php echo $studyTopicsOptions; ?>
                        </select>
                    </div>
                    <div class="col">
                        <select class="form-control" name="studyActivity">
                            <option value="" selected disabled>Tegevus</option>
                            <?php echo $studyActivitiesOptions; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <br>
                        <label>Tegevusele kulunud aeg:</label>
                        <input class="form-control" type="number" min=".25" max="24" step=".25" name="elapsedTime">
                    </div>
                </div><br>
                <input type="submit" class="btn btn-secondary" name="studyBtn" value="Salvesta tegevus!"><br><br>
                <span><?php echo $studyError; ?></span>
            </form>
        </div>
    </section>
</div>