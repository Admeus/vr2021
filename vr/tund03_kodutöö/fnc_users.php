<?php

//sessiooni käivitamine või kasutamine, server saadab mingi cookied masinasse ja talletab. tektitasb $_SESSION massiivi
//session_start();

function signUp($name, $surname, $email, $gender, $birthDate, $password)
{
    $notice = null;

    //Kontrollime, kas kasutaja on juba olemast
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT id FROM vr20_users WHERE email=?");
    echo $conn->error;

    $stmt->bind_param("s", $email);
    $stmt->bind_result($idFromDB);
    $stmt->execute();


    if ($stmt->fetch()) {

        $notice = "Sisestatud e-mailiga on juba kasutaja olemas!";
        $stmt->close();
        $conn->close();

        return $notice;
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO vr20_users (firstname, lastname, birthdate, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        echo $conn->error;

        //krüpteerin parooli
        //"cost" - määrab ära kui palju üritatakse krüpteerimisega vaeva näha, "salt" - teeb parooli natukene juhuslikumaks (lisatakse lisa string, erilised tähemärgid, muudab räsi juhuslikumaks), sha1 kürpteerimisalgoritm
        $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
        $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);

        $stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);

        if ($stmt->execute()) {
            $notice = "ok";
        } else {
            $notice = $stmt->error;
        }

        $stmt->close();
        $conn->close();

        return $notice;
    }
}

function signIn($email, $password)
{
    $notice = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT password FROM vr20_users WHERE email = ?");
    echo $conn->error;

    $stmt->bind_param("s", $email);
    $stmt->bind_result($passwordFromDB);
    $stmt->execute();

    if ($stmt->fetch()) {

        if (password_verify($password, $passwordFromDB)) {
            $stmt->close();

            $stmt = $conn->prepare("SELECT id, firstname, lastname FROM vr20_users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->bind_result($idFromDB, $firstnameFromDB, $lastnameFromDB);
            echo $conn->error;
            $stmt->execute();
            $stmt->fetch();

            $_SESSION["userid"] = $idFromDB;
            $_SESSION["userFirstName"] = $firstnameFromDB;
            $_SESSION["userLastName"] = $lastnameFromDB;

            $stmt->close();
            $conn->close();

            header("Location: home.php");
            exit();
        } else {
            $notice = "Vale salasõna!";
        }
    } else {
        $notice = "Sellist kasutajat (" . $email . ") ei leitud!";
    }

    $stmt->close();
    $conn->close();

    return $notice;
}
