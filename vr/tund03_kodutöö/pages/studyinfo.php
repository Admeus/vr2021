<?php

// kas on sisseloginud
if (!isset($_SESSION["userid"])) {
    //Jõuga avalehele
    header("Location: ../index.php");
}

require("fnc_study.php");

$studyTableHTML = getStudyTableHTML();

?>
<div class="container" style="max-width: 80%; margin-top:100px;">
    <section class="text-center">
        <h1 class="jumbotron-heading">Õppetegevuse info</h1>
        <p class="lead text-muted">Siin lehel kuvatakse Teie tehtud õppetegevused!</p>
        <br>
    </section>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Õppeaine</th>
                <th scope="col">Tegevus</th>
                <th scope="col">Kulunud aeg</th>
                <th scope="col">Kuupäev</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $studyTableHTML; ?>
        </tbody>
    </table>
</div>