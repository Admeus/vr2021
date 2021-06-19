<?php

$myname 		= 'Ainar Kiison';
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
$semesterStart = new DateTime("2021-01-27");
$semesterEnd = new DateTime("2021-06-22");
$semesterDuration = $semesterStart->diff($semesterEnd);
$today = new DateTime("Now");
$fromSemesterStart = $semesterStart->diff($today);

if($today < $semesterStart) {
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

if($photoCount!=0){
	$randomIMGList = [];
	$randomImgHTML = '';

	do {
		$randomIMG = $photoList[mt_rand(0, $photoCount - 1)];
		if(!in_array($randomIMG, $randomIMGList)){
			array_push($randomIMGList, $randomIMG);
			$randomImgHTML .= '<img src="' . $picsDir . $randomIMG . '" alt="Juhuslik Pilt Haapsalust"></img>' . "\n";
		} 
	} while (count($randomIMGList)<=2);

} else {
	$randomImgHTML = '<p>Ühtegi pilti pole, mida kuvada</p>';
}

?>

<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<style>
	.morning {
		background-color: yellow;
	}

	.night {
		background-color: orange;
	}
</style>
<body class=<?php echo $bgclass; ?>>
	<h1 class="jumbotron-heading"><?php echo $myname; ?></h1>
	<p class="lead text-muted">See leht on valminud! õppetöö raames!</p>
	<?php
	echo $timeHTML . $partOfDayHTML . $semesterProgressHTML . $randomImgHTML;

	?>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>