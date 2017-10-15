<?php
	if ( !isset($_SERVER['PHP_AUTH_USER'] ) || !isset( $_SERVER['PHP_AUTH_PW'] ) ||    
    $_SERVER['PHP_AUTH_USER'] != "love" || $_SERVER['PHP_AUTH_PW'] != "thuyiscool2017" ) {
        header( 'WWW-Authenticate: Basic realm="Authenticated ticket sellers"' );
        header( 'HTTP/1.0 401 Unauthorized' );
        die("<h1>Nice try, Stephen Fry.</h1>");
    }
	
	?>
	
	<html>
	<head>
		<title>Current Tickets</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		
		<script>
			function toggleAll() {
				i = 0;
				state = document.getElementById("leadBox").checked;
				while (true) {
					try {
						checkbox = document.getElementById("check[" + i + "]");
						checkbox.checked = state;
						i++;
					}
					catch (err) {
						break;
					}
				}
			}
		</script>
	</head>
	<body>
	<div class="container">
	<ul class="nav nav-pills">
		<li role="presentation"><a href="selltickets.php">Sell</a></li>
		<li role="presentation"><a href="sellgroup.php">Sell Group</a></li>
		<li role="presentation" class="active"><a href="">Ticket Overview</a></li>
	</ul>
	</div>
	
	<div class="container-fluid">
	<?
	require_once('tickets/PHPMailer-master/class.phpmailer.php');
	
	$config = parse_ini_file('../../../ticketsdb.ini');

	$conn = new mysqli('localhost', $config['username'], $config['password'], $config['dbname']);
	
	$sql = "SELECT * from `tickets` ORDER BY `createdAt` asc";
	$result = $conn->query($sql);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$optionBox = $_POST["optionBox"];
		$checkboxes = $_POST["check"];
		
		if ($optionBox == "resendEmail") {
			$counter = 0;
			while ($row = $result->fetch_assoc()) {
				if ($checkboxes[$counter] === "on") {
					$email = new PHPMailer();
					$email->From 		= 'love@scuvsa.org';
					$email->FromName 	= 'SCU VSA';
					$email->Subject		= 'Your ticket to ONE FOOT IN, ONE FOOT OUT';
					ob_start();
					include('tickets/email.php');
					$email->Body		= ob_get_contents();
					ob_end_clean();
					$email->IsHTML(true);
					$email->AddAddress($row["email"]);
					$email->AddAttachment('tickets/archive/' . $row["validation"] . '.pdf',$row["firstName"] . '-' . $row["lastName"] . '-Ticket-0.pdf');
					$email->Send();
				}
				$counter++;
			}
		} else {
			$paid = $optionBox == "true" ? 1 : 0;
			
			$counter = 0;
			while ($row = $result->fetch_assoc()) {
				if ($checkboxes[$counter] === "on") {
					$sqlUpdate = "UPDATE `tickets` SET `paid`=" . $paid . " WHERE `validation`='" . $row["validation"] . "'";
					$conn->query($sqlUpdate);
				}
				$counter++;
			}
		}
	}
	
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
		?>
		<form action="currenttickets.php" method="post">
			<select name="optionBox">
				<option value="true">Mark Paid</option>
				<option value="false">Mark Unpaid</option>
				<option value="resendEmail">Resend Ticket Email</option>
			</select>
			<button type="submit" class="btn btn-primary">Do</button>
			<table class="table table-striped">
				<thead>
					<tr>
						<th><input type="checkbox" onchange="javascript:toggleAll();" id="leadBox"/></th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Phone Number</th>
						<th>Email</th>
						<th>Activated?</th>
						<th>Paid?</th>
						<th>Purchase Time</th>
						<th>Activation Time</th>
						<th>Board/Group Discount?</th>
						<th>Club/Group Name</th>
						<th>Online Order?</th>
						<th>Paypal Transaction ID</th>
						<th>Paypal Trx Status</th>
					</tr>
				</thead>
				<tbody>
				<?
					$counter = 0;
					$money = 0;
					while ($row = $result->fetch_assoc()) {
						?>
						<tr>
							<td><input type="checkbox" name="check[<? echo $counter ?>]" id="check[<? echo $counter ?>]" /></td>
							<td><? echo $row["firstName"] ?></td>
							<td><? echo $row["lastName"] ?></td>
							<td><? echo $row["phoneNumber"] ?></td>
							<td><? echo $row["email"] ?></td>
							<td><? echo $row["isActive"] == 1 ? "<b>Yes</b>" : "No" ?></td>
							<td><? echo $row["paid"] == 1 ? "Yes" : "<b>No</b>" ?></td>
							<td><? echo $row["createdAt"] ?></td>
							<td><? echo $row["activatedAt"] ?></td>
							<td><? echo $row["boardDiscount"] == 1 ? "<b>Yes</b>" : "No" ?></td>
							<td><? echo $row["board"] ?></td>
							<td><? echo $row["onlineOrder"] == 1 ? "<b>Yes</b>" : "No" ?></td>
							<td><? echo $row["paypalTxnId"] ?></td>
							<td><? echo $row["paypalStatus"] ?></td>
						</tr>
						<?
						$counter++;
						if ($row["paid"] == 1 && $row["boardDiscount"] == 1) {
							$money += 8;
						}
						else if ($row["paid"] == 1 && $row["purchasedAtDoor"] == 1) {
							$money += 12;
						}
						else if ($row["paid"] == 1) {
							$money += 10;
						}
					}
				?>
				</tbody>
			</table>
		</form>
		<h2>Tickets Sold: <? echo $counter ?></h2>
		<h3>Gross Sales: $<? echo ($money) ?></h3>
		<?	
	} else {
		echo "<h2>No tickets sold yet.</h2>";
	}
?>
</div>
</body>
</html>