<?php
require("../../../../configuration.php");
require("fnc_users.php");

require("classes/Session.class.php");

SessionManager::sessionStart("vr20", 0, "/~caspar.ruul/", "tigu.hk.tlu.ee");

$myname 		= 'Caspar Ruul';
$fulltimenow	= date("d.m.Y H:i:s");
$timeHTML		= "<p>Lehe avamise hetkel oli aeg: <strong>$fulltimenow</strong></p>";
$hourNow		= date("H");
$partOfDay		= "hägune aeg";


if ($hourNow < 10) {
	$partOfDay = 'hommik';
} elseif ($hourNow >= 10 && $hourNow < 18) {
	$partOfDay = 'aeg aktiivselt tegutseda';
}

$partOfDayHTML = "<p>Käes on $partOfDay!</p> \n";

//tausta pildi muutmine, hommik / õhtu
if ($hourNow > 6 && $hourNow < 12) {
	$bgclass = '"morning"';
} else {
	$bgclass = '"night"';
}

// info semestri kulgemise kohta
$semesterStart = new DateTime("2020-01-27");
$semesterEnd = new DateTime("2020-06-22");
$semesterDuration = $semesterStart->diff($semesterEnd);
$today = new DateTime("Now");
$fromSemesterStart = $semesterStart->diff($today);

if ($today < $semesterStart) {
	$semesterProgressHTML = '<p>Semester ei ole veel alanud!</p>';
} elseif ($today > $semesterEnd) {
	$semesterProgressHTML = '<p>Semester on läbi!</p>';
} else {
	$semesterProgressHTML = '<p>Semester on hoos: <meter min="0" max="';
	$semesterProgressHTML .= $semesterDuration->format("%r%a");
	$semesterProgressHTML .= '" value="';
	$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
	$semesterProgressHTML .= '"></meter></p>' . "\n";
}

// Piltide osa
$picsDir = "../../pics/";
$photoTypesAllow = ['image/jpeg', 'image/png'];
$allFiles = array_slice(scandir($picsDir), 2);
$photoList = [];

foreach ($allFiles as $file) {
	$fileInfo = getimagesize($picsDir . $file);
	if (in_array($fileInfo["mime"], $photoTypesAllow)) {
		array_push($photoList, $file);
	}
}

$photoCount = count($photoList);

if ($photoCount != 0) {
	$randomIMGList = [];
	$randomImgHTML = '';

	do {
		$randomIMG = $photoList[mt_rand(0, $photoCount - 1)];
		if (!in_array($randomIMG, $randomIMGList)) {
			array_push($randomIMGList, $randomIMG);
			$randomImgHTML .= '<img src="' . $picsDir . $randomIMG . '" alt="Juhuslik Pilt Haapsalust"></img>' . "\n";
		}
	} while (count($randomIMGList) <= 2);
} else {
	$randomImgHTML = '<p>Ühtegi pilti pole, mida kuvada</p>';
}

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
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<style>
	.morning {
		background-color: lightblue;
	}

	.night {
		background-color: darkgray;
	}
</style>

<body class=<?php echo $bgclass; ?>>
	<h1><?php echo $myname; ?></h1>
	<p>See leht on valminud! õppetöö raames!</p>

	<h2>Logi sisse</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>E-mail (kasutajatunnus):</label><br>
		<input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
		<label>Salasõna:</label><br>
		<input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
		<input name="login" type="submit" value="Loo sisse"></span><span><?php echo $notice; ?></span>
	</form>

	<p>Loo endale <a href="newuser.php">kasutajakonto</a>!</p>
	<?php
	echo $timeHTML . $partOfDayHTML . $semesterProgressHTML . $randomImgHTML;

	?>
</body>

</html>