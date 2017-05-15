<html>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
	$conn = new PDO("mysql:host=$servername;dbname=teacherwordpairings", $username, $password);	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	$word = $_GET['word'];

	$getWordIdSQL = "SELECT * FROM words WHERE word='$word'";
	$wordIdSQLResult = $conn->query($getWordIdSQL)->fetchAll();

	if (sizeof($wordIdSQLResult) == 0) {
		print("<p>This word does not exist</p>");
		return;
	}

	$wordId = $wordIdSQLResult[0]['id'];

	$getTeachersPairedWithWordSQL = "SELECT * FROM teachertoword WHERE word='$wordId'";
	$getTeachersPairedWithWordSQLResult = $conn->query($getTeachersPairedWithWordSQL)->fetchAll();

	if (sizeof($getTeachersPairedWithWordSQLResult) == 0) {
		print("<p>There are no teachers paired with this word</p>");
		return;
	}

	print("<p>The following teachers are paired with the word " . $word . "</p>");

	print("<table>");

	foreach ($getTeachersPairedWithWordSQLResult as $curPairing) {
		
		$curPairingTeacherId = $curPairing['teacher'];

		$getTeacherNameFromIdSQL = "SELECT * FROM teachers WHERE id=$curPairingTeacherId";
		$curTeacherNameResult = $conn->query($getTeacherNameFromIdSQL)->fetchAll();
		$curTeacherName = $curTeacherNameResult[0]['name'];

		$numOfPairings = $curPairing['count'];

		print("<tr>");

		print("<td>" . $curTeacherName . "</td>");

		print("<td>" . $numOfPairings . "</td>");

		print("</tr>");
	}

} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>