<?php

// kas on logitud sisse?
if (!isset($_SESSION["userid"])) {
    //Home
    header("Location: ../index.php");
}

require("fnc_news.php");
?>


<div class="jumbotron">
    <div class="container">
        <h1 class="display-3">Veebirakendused ja nende loomine 2021</h1>
        <p>See leht on valminud õppetöö raames!</p>
    </div>
</div>

<div class="container">
    <!-- Example row of columns -->
    <div class="row">


    </div>

</div> <!-- /container -->