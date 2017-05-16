<html>
<head><title>Add New Pair</title></head>

<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
	$conn = new PDO("mysql:host=$servername;dbname=teacherwordpairings", $username, $password);	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	$teacher = $_GET['teacher'];
	$word = $_GET['word'];

	print("<p>" . $teacher . "</p>");
	print("<p>" . $word . "</p>");

	$getIfWordExistsSQL = "SELECT * FROM words WHERE word='$word'";
	$wordExistsResult = $conn->query($getIfWordExistsSQL)->fetchAll();
	print("<p>" . sizeof($wordExistsResult) . "</p>");
	if (sizeof($wordExistsResult) == 0) {
		$submitNewWordSQL = "INSERT INTO words (word) VALUES ('$word')";
		$conn->exec($submitNewWordSQL);
	}

	$getIfTeacherExistsSQL = "SELECT * FROM teachers WHERE name='$teacher'";
	$teacherExistsResult = $conn->query($getIfTeacherExistsSQL)->fetchAll();
	print("<p>" . sizeof($teacherExistsResult) . "</p>");
	if (sizeof($teacherExistsResult) == 0) {
		$submitNewTeacherSQL = "INSERT INTO teachers (name) VALUES ('$teacher')";
		$conn->exec($submitNewTeacherSQL);
	}

	$getTeacherIdSQL = "SELECT * FROM teachers";

	$getIfPairingExistsSQL = "SELECT * FROM teachertoword WHERE teacher='$teacher' AND word='$word'";
	$pairingExistsResult = $conn->query($getIfPairingExistsSQL)->fetchAll();
	print("<p>" . sizeof($pairingExistsResult) . "</p>");
	if (sizeof($pairingExistsResult) == 0) {

		$submitNewPairingSQL = "INSERT INTO teachertoword VALUES ('$teacher', '$word', 1)";
		$conn->exec($submitNewPairingSQL);
	} else {
		foreach ($pairingExistsResult as $row) {
			$pairCount = $row['count'];
			$updatePairingCountSQL = "UPDATE teachertoword SET count=$pairCount + 1 WHERE teacher='$teacher' AND word='$word'";
			$conn->exec($updatePairingCountSQL);
			break;
		}
	}

	print "<p>Successfully added pairing</p>";
} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>