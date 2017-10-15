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
		<title>Ticket Sales</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	</head>
	<body>
	<div class="container">
	<ul class="nav nav-pills">
		<li role="presentation" class="active"><a href="">Sell</a></li>
		<li role="presentation"><a href="sellgroup.php">Sell Group</a></li>
		<li role="presentation"><a href="currenttickets.php">Ticket Overview</a></li>
	</ul>
	
	<?
	if ($_POST)
	{
		$config = parse_ini_file('../../../ticketsdb.ini');

		$conn = new mysqli('localhost', $config['username'], $config['password'], $config['dbname']);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$key = md5(microtime().rand());
		$firstName = $_POST["first"];
		$lastName = $_POST["last"];
		$phoneNumber = $_POST["pn"];
		$email = $_POST["email"];
		$paid = $_POST["paid"] == "on" ? 1 : 0;
		$boardDiscount = $_POST["bd"] == "on" ? 1 : 0;
		$board = $_POST["board"];

		$validation = md5($firstName . ":" . $lastName . ":" . $key);
		$sql = "INSERT INTO `tickets`(`firstName`,`lastName`,`phoneNumber`,`email`,`paid`,`createdAt`,`boardDiscount`,`board`,`validation`) VALUES ('" . addslashes($firstName) . "','" . addslashes($lastName) . "','" . addslashes($phoneNumber) . "','" . addslashes($email) . "'," . $paid . ",DATE_SUB(NOW(), INTERVAL 3 HOUR)," . $boardDiscount . ",'" . addslashes($board) . "','" . addslashes($validation) ."')";

		if ($conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br/>" . $conn->error;
		}
		
		// Create ticket pdf in case someone fucks up
		$url = 'https://show.scuvsa.org/tickets/ticket.php';
		$data = array('first' => $firstName, 'last' => $lastName, 'key' => $key, 'email' => $email, 'board' => $board, 'send' => 0);
		
		$options = array(
			'http' => array(
				'header'	=> "Content-type: application/x-www-form-urlencoded\r\n",
				'method'	=> 'POST',
				'content'	=> http_build_query($data)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		$conn->close();
		?>
		<table class="table">
			<thead>
				<tr>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Phone Number</th>
					<th>Email</th>
					<th>Paid?</th>
					<th>Board/Group Discount?</th>
					<th>Club/Group Name</th>
					<th>Ticket</th>
					<th>Email Ticket</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo htmlentities($firstName) ?></td>
					<td><?php echo htmlentities($lastName) ?></td>
					<td><?php echo htmlentities($phoneNumber) ?></td>
					<td><?php echo htmlentities($email) ?></td>
					<td><?php echo htmlentities($paid) ?></td>
					<td><?php echo htmlentities($boardDiscount) ?></td>
					<td><?php echo htmlentities($board) ?></td>
					<td>
						<form action="tickets/ticket.php" method="post" target="_blank">
							<input type="hidden" name="first" value="<?php echo $firstName ?>" />
							<input type="hidden" name="last" value="<?php echo $lastName ?>" />
							<input type="hidden" name="board" value="<?php echo $board ?>" />
							<input type="hidden" name="key" value="<?php echo $key ?>" />
							<button type="submit" class="btn btn-primary">Get Ticket</button>
						</form>
					</td>
					<td>
						<form action="tickets/ticket.php" method="post" target="_blank">
							<input type="hidden" name="first" value="<?php echo $firstName ?>" />
							<input type="hidden" name="last" value="<?php echo $lastName ?>" />
							<input type="hidden" name="board" value="<?php echo $board ?>" />
							<input type="hidden" name="email" value="<?php echo $email ?>" />
							<input type="hidden" name="key" value="<?php echo $key ?>" />
							<input type="hidden" name="send" value="1" />
							<button type="submit" class="btn btn-primary">Email Ticket</button>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
		<?
	} else {
		?>
		<form action="selltickets.php" method="post">
			<div class="form-group">
				<label for="first">First Name</label>
				<input type="text" class="form-control" name="first" id="first" placeholder="First" />
			</div>
			<div class="form-group">
				<label for="last">Last Name</label>
				<input type="text" class="form-control" name="last" id="last" placeholder="Last" />
			</div>
			<div class="form-group">
				<label for="email">Email Address</label>
				<input type="email" class="form-control" name="email" id="email" placeholder="example@scu.edu" />
			</div>
			<div class="form-group">
				<label for="pn">Phone Number</label>
				<input type="tel" class="form-control" name="pn" id="pn" placeholder="(123) 456-7890" />
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="bd"> Board/Group Discount?
				</label>
			</div>
			<div class="form-group">
				<label for="board">Board/Group Name</label>
				<input type="text" class="form-control" name="board" id="board" placeholder="Board/Group Name" />
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="paid" checked> Paid?
				</label>
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		<?
	}
?>

</div>

</body>
</html>