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
		
		<script>
			var currentRow = 1;			
			
			function addRow() {
				var table = document.getElementById("groupTable");
				
				var row = table.insertRow(-1);
				
				var firstCell = row.insertCell(0);
				var lastCell = row.insertCell(1);
				var emailCell = row.insertCell(2);
				var pnCell = row.insertCell(3);
				
				firstCell.innerHTML = '<input type="text" class="form-control" name="first[' + currentRow + ']" placeholder="First" id="first[' + currentRow + ']"] />';
				lastCell.innerHTML = '<input type="text" class="form-control" name="last[' + currentRow + ']" placeholder="Last" id="last[' + currentRow + ']" />';
				emailCell.innerHTML = '<input type="email" class="form-control" name="email[' + currentRow + ']" placeholder="example@scu.edu" id="email[' + currentRow + ']" />';
				pnCell.innerHTML = '<input type="tel" class="form-control" name="pn[' + currentRow + ']" placeholder="(123) 456-7890" id="pn[' + currentRow + ']" />';
				
				currentRow++;
			}
			
			function addRows(num) {
				for (i = 0; i < num; i++) {
					addRow();
				}
			}
			
			function copyDown(base) {
				var val = document.getElementById(base + '[0]').value;
				for (i = 1; i < currentRow; i++) {
					var e = document.getElementById(base + '[' + i + ']');
					e.value = val;
				}
			}
		</script>		
	</head>
	<body onLoad="addRows(4)">
	<div class="container">
	<ul class="nav nav-pills">
		<li role="presentation"><a href="selltickets.php">Sell</a></li>
		<li role="presentation" class="active"><a href="">Sell Group</a></li>
		<li role="presentation"><a href="currenttickets.php">Ticket Overview</a></li>
	</ul>
	
	<?
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$config = parse_ini_file('../../../ticketsdb.ini');

		$conn = new mysqli('localhost', $config['username'], $config['password'], $config['dbname']);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$firstName = $_POST["first"];
		$lastName = $_POST["last"];
		$phoneNumber = $_POST["pn"];
		$email = $_POST["email"];
		$paid = $_POST["paid"] == "on" ? 1 : 0;
		$board = $_POST["board"];
		$key = array();
		
		for ($i = 0; $i < count($email); $i++) {
			if (!empty($email[$i])) {
				$key[$i] = md5(microtime().rand());
				$validation = md5($firstName[$i] . ":" . $lastName[$i] . ":" . $key[$i]);
				$sql = "INSERT INTO `tickets`(`firstName`,`lastName`,`phoneNumber`,`email`,`paid`,`createdAt`,`boardDiscount`,`board`,`validation`) VALUES ('" . addslashes($firstName[$i]) . "','" . addslashes($lastName[$i]) . "','" . addslashes($phoneNumber[$i]) . "','" . addslashes($email[$i]) . "'," . $paid . ",DATE_SUB(NOW(), INTERVAL 3 HOUR),1,'" . addslashes($board) . "','" . addslashes($validation) ."')";

				if ($conn->query($sql) === FALSE) {
					echo "Error: " . $sql . "<br/>" . $conn->error;
				}
				
				// Create ticket pdfs in case someone fucks up
				$url = 'https://show.scuvsa.org/tickets/ticket.php';
				$data = array('first' => $firstName[$i], 'last' => $lastName[$i], 'key' => $key[$i], 'email' => $email[$i], 'board' => $board, 'send' => 0);
				
				$options = array(
					'http' => array(
						'header'	=> "Content-type: application/x-www-form-urlencoded\r\n",
						'method'	=> 'POST',
						'content'	=> http_build_query($data)
					)
				);
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
			}
		}

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
				<?
				for ($i = 0; $i < count($email); $i++) {
					if (!empty($email[$i])) {
					?>
					<tr>
						<td><?php echo htmlentities($firstName[$i]) ?></td>
						<td><?php echo htmlentities($lastName[$i]) ?></td>
						<td><?php echo htmlentities($phoneNumber[$i]) ?></td>
						<td><?php echo htmlentities($email[$i]) ?></td>
						<td><?php echo htmlentities($paid) ?></td>
						<td><?php echo htmlentities($boardDiscount) ?></td>
						<td><?php echo htmlentities($board) ?></td>
						<td>
							<form action="tickets/ticket.php" method="post" target="_blank">
								<input type="hidden" name="first" value="<?php echo $firstName[$i] ?>" />
								<input type="hidden" name="last" value="<?php echo $lastName[$i] ?>" />
								<input type="hidden" name="board" value="<?php echo $board ?>" />
								<input type="hidden" name="key" value="<?php echo $key[$i] ?>" />
								<button type="submit" class="btn btn-primary">Get Ticket</button>
							</form>
						</td>
						<td>
							<form action="tickets/ticket.php" method="post" target="_blank">
								<input type="hidden" name="first" value="<?php echo $firstName[$i] ?>" />
								<input type="hidden" name="last" value="<?php echo $lastName[$i] ?>" />
								<input type="hidden" name="board" value="<?php echo $board ?>" />
								<input type="hidden" name="email" value="<?php echo $email[$i] ?>" />
								<input type="hidden" name="key" value="<?php echo $key[$i] ?>" />
								<input type="hidden" name="send" value="1"/>
								<button type="submit" class="btn btn-primary">Email Ticket</button>
							</form>
						</td>
					</tr>
					<?
					}
				}
				?>				
			</tbody>
		</table>
		<?
	} else {
		?>
		<form action="sellgroup.php" method="post">
			<div class="form-group">
				<label for="board">Board/Group Name</label>
				<input type="text" class="form-control" name="board" id="board" placeholder="Board/Group Name" />
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="paid" checked> Paid?
				</label>
			</div>
			
			<button class="btn btn-success" onclick="javascript:addRow()" type="button">Add Row</button>
			<button class="btn btn-success" onclick="javascript:addRows(5)" type="button">Add 5 Rows</button>
			
			<table class="table" id="groupTable">
				<thead>
					<tr>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email Address</th>
						<th>Phone Number</th>
					</tr>
				</thead>
				<tbody>
					<td>
						<input type="text" class="form-control" name="first[0]" placeholder="First" id="first[0]" />
						<small><a href="javascript:copyDown('first')">Copy Down</a></small>
					</td>
					<td>
						<input type="text" class="form-control" name="last[0]" placeholder="Last" id="last[0]" />
						<small><a href="javascript:copyDown('last')">Copy Down</a></small>
					</td>
					<td>
						<input type="email" class="form-control" name="email[0]" placeholder="example@scu.edu" id="email[0]" />
						<small><a href="javascript:copyDown('email')">Copy Down</a></small>
					</td>
					<td>
						<input type="tel" class="form-control" name="pn[0]" placeholder="(123) 456-7890" id="pn[0]" />
						<small><a href="javascript:copyDown('pn')">Copy Down</a></small>
					</td>
				</tbody>
			</table>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
		<?
	}
?>
</div>
</body>
</html>