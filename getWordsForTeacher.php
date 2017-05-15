<html>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
	$conn = new PDO("mysql:host=$servername;dbname=teacherwordpairings", $username, $password);	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	$teacher = $_GET['teacher'];

	$getTeacherIdSQL = "SELECT * FROM teachers WHERE name='$teacher'";
	$teacherIdSQLResult = $conn->query($getTeacherIdSQL)->fetchAll();

	if (sizeof($teacherIdSQLResult) == 0) {
		print("<p>This teacher does not exist</p>");
		return;
	}

	$teacherId = $teacherIdSQLResult[0]['id'];

	$getWordsPairedWithTeacherSQL = "SELECT * FROM teachertoword WHERE teacher=$teacherId ORDER BY count DESC";
	$getWordsPairedWithTeacherSQLResult = $conn->query($getWordsPairedWithTeacherSQL)->fetchAll();

	if (sizeof($getWordsPairedWithTeacherSQLResult) == 0) {
		print("<p>There are no words paired with this teacher</p>");
		return;
	}

	print("<p>The following words are paired with the teacher " . $teacher . "</p>");

	print("<table>");

	foreach ($getWordsPairedWithTeacherSQLResult as $curPairing) {
		
		$curPairingWordId = $curPairing['word'];

		$getWordFromIdSQL = "SELECT * FROM words WHERE id=$curPairingWordId";
		$curWordResult = $conn->query($getWordFromIdSQL)->fetchAll();
		$curWord = $curWordResult[0]['word'];

		$numOfPairings = $curPairing['count'];

		print("<tr>");

		print("<td>" . $curWord . "</td>");

		print("<td>" . $numOfPairings . "</td>");

		print("</tr>");
	}

} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>