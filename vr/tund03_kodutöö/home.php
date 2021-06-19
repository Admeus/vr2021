<?php
require("classes/Session.class.php");
SessionManager::sessionStart("vr20", 0, "/~ainar.kiison/", "tigu.hk.tlu.ee");

// kas on logitud sisse?
if (!isset($_SESSION["userid"])) {
    //Homepagele
    header("Location: index.php");
}

require("../../../../configuration.php");
require("fnc_global.php");


//log out
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
}

if (isset($_GET["page"])) {
    $currentpage = "?page=" . $_GET["page"];
}



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
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="home.php?page=first">Tere <?php echo $_SESSION['userFirstName'] . " " . $_SESSION['userLastName']; ?>!</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php?page=first">Home <span class="sr-only">(current)</span></a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="home.php?page=news" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Uudised</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                        <a class="dropdown-item" href="home.php?page=news">Loe uudiseid</a>
                        <a class=" dropdown-item" href="home.php?page=addnews">Sisesta uudis</a>
                    </div>
                </li>
            </ul>
            <a class="btn btn-secondary btn-sm" href="?logout=1">Logi välja</a>
        </div>
    </nav>

    <main role="main">

        <?php
        $p = array("first", "addnews", "news", "study", "studyinfo");
        if (isset($_GET['page']) and in_array($_GET['page'], $p)) {
            include('pages/' . $_GET['page'] . '.php');
        } elseif (!empty($_GET['page'])) {
            include('pages/404.php');
        } else {
            include('pages/first.php');
        }
        ?>

    </main>


    

    </div>


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>