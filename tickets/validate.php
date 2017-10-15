<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<title>
	<?php
		define("ACTIVATED", true);
		
		if (!ACTIVATED)
			die("Scanning your ticket early? I get that you're curious, but please don't.</title></head><body><h2>Scanning your ticket early? I get that you're curious, but please don't. It messes us up.</h2></html>");
		
		$config = parse_ini_file('../../../../ticketsdb.ini');
			
		$servername = "localhost";
		$username = $config['username'];
		$password = $config['password'];
		$dbname = $config['dbname'];

		$conn = new mysqli($servername, $username, $password, $dbname);
		
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
		$key = $_GET["key"];
		$firstName = $_GET["first"];
		$lastName = $_GET["last"];
		
		$validate = MD5($firstName . ":" . $lastName . ":" . $key);
		
		$sqlCheck = "SELECT `isActive`,`firstName`,`lastName`,`activatedAt` FROM `tickets` WHERE `validation`='" . $validate . "'";
		$result = $conn->query($sqlCheck);
		
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			if ($row["isActive"] == 0) {
				$sqlUpdate = "UPDATE `tickets` SET `isActive`=1,`activatedAt`=DATE_SUB(NOW(), INTERVAL 3 HOUR) WHERE `validation`='" . $validate . "'";
				$conn->query($sqlUpdate);
				echo "Guest " . htmlentities($firstName) . " " . htmlentities($lastName) . " successfully validated. Welcome!";
			}
			else {
				echo "Guest " . htmlentities($firstName) . " " . htmlentities($lastName) . " already activated this at ".  $row["activatedAt"] ."!";
			}
		}
		else {
			echo "Invalid ticket!";
		}
	?>
	</title>	
</head>
<body>

<div class="container">
	<h1>
		<script type="text/javascript">
			document.write(document.title);
		</script>
	</h1>

</div>
</body>

</html>