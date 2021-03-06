<?php

function getStudyTopicsOptions()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT id, course FROM vr20_studytopics order by course asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $courseNameFromDB);
    $stmt->execute();


    while ($stmt->fetch()) {
        $response .= '<option value="' . $idFromDB . '">' . $courseNameFromDB . '</option>\n';
    }

    if ($response == null) {
        $response = "Kursuste nimed puuduvad!";
    }

    $stmt->close();
    $conn->close();
    return $response;
}

function getStudyActivitiesOptions()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT id, activity FROM vr20_studyactivities order by activity asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $activityNameFromDB);
    $stmt->execute();


    while ($stmt->fetch()) {
        $response .= '<option value="' . $idFromDB . '">' . $activityNameFromDB . '</option>\n';
    }

    if ($response == null) {
        $response = "Tegevuste nimed puuduvad!";
    }

    $stmt->close();
    $conn->close();
    return $response;
}

function saveStudy($studyTopicId, $studyActivity, $elapsedTime)
{

    $response = null;
    //Loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

    //Valmistan ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_studylog (course, activity, time, userid) VALUES (?, ?, ?, ?)");
    echo $conn->error;

    //Seon päringuga tegelikud andmed

    // i - integer
    // s - string
    // d - decimal
    $stmt->bind_param("isdi", $studyTopicId, $studyActivity, $elapsedTime, $_SESSION["userid"]);

    if ($stmt->execute()) {
        $response = 1;
    } else {
        $response = 0;
        echo $stmt->error;
    }

    //Sulgen päringu ja andmebaasi ühenduse.
    $stmt->close();
    $conn->close();
    return $response;
}

function getStudyTableHTML()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT sl.id, st.course, sa.activity, time, day 
                                FROM vr20_studylog sl 
                                JOIN vr20_studytopics st on sl.course=st.id
                                JOIN vr20_studyactivities sa on sl.activity=sa.id
                                where sl.userid = ?
                                order by id asc");
    echo $conn->error;
    $stmt->bind_param("i", $_SESSION["userid"]);

    $stmt->bind_result($idFromDB, $courseNameFromDB, $activityNameFromDB, $elapsedTimeFromDB, $dateFromDB);
    $stmt->execute();

    $rowCount = 1;
    while ($stmt->fetch()) {

        $response .= '<tr>
        <th scope="row">' . $rowCount . '</th>
        <td>' . $courseNameFromDB . '</td>
        <td>' . $activityNameFromDB . '</td>
        <td>' . $elapsedTimeFromDB . '</td>
        <td>' . $dateFromDB . '</td>
        </tr>';

        $rowCount += 1;
    }

    if ($response == null) {
        $response = "Ühtegi tegevust ei ole lisatud!";
    }

    $stmt->close();
    $conn->close();
    return $response;
}
