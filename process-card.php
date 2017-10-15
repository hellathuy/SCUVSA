<?php
require 'vendor/autoload.php';
require_once('tickets/PHPMailer-master/class.phpmailer.php');

$location_id = '1H12JJ33WP5E7';
$access_token = 'sq0atp-UFKgh4A0cYsFGNk1mQ30XA';

# Helps ensure this code has been reached via form submission
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	error_log("Received a non-POST request");
	echo "Request not allowed";
	http_response_code(405);
	return;
}
# Fail if the card form didn't send a value for `nonce` to the server
$nonce = $_POST['nonce'];
if (is_null($nonce)) {
	echo "Invalid card data";
	http_response_code(422);
	return;
}
$subtotal = 0;
if ($_POST['numTickets'] >= 5) {
	$subtotal = $_POST['numTickets'] * 8;
}
else {
	$subtotal = $_POST['numTickets'] * 10;
}
$total = ($subtotal + 0.15) / 0.965;
$total = ceil($total * 100);
	
$transaction_api = new \SquareConnect\Api\TransactionApi();
$request_body = array (
	"card_nonce" => $nonce,
	# Monetary amounts are specified in the smallest unit of the applicable currency.
	# This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
	
	"amount_money" => array (
		"amount" => $total,
		"currency" => "USD"
	),
	# Every payment you process with the SDK must have a unique idempotency key.
	# If you're unsure whether a particular payment succeeded, you can reattempt
	# it with the same idempotency key without worrying about double charging
	# the buyer.
	"idempotency_key" => uniqid()
);
# The SDK throws an exception if a Connect endpoint responds with anything besides
# a 200-level HTTP code. This block catches any exceptions that occur from the request.
try {
	$result = $transaction_api->charge($access_token, $location_id, $request_body);
	
	$config = parse_ini_file('../../../ticketsdb.ini');
	$servername = "localhost";
	$username = $config['username'];
	$password = $config['password'];
	$dbname = $config['dbname'];
	
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$phone = $_POST['phone'];
	$txn_id = $result[transaction][tenders][0][transaction_id];
	$payer_email = $_POST['email'];
	$quantity = $_POST['numTickets'];
	$gross = $total;
	$fee = $total / 100 - $subtotal;
	$boardDiscount = $quantity > 4 ? 1 : 0;
	$groupName = $_POST['groupName'];
	
	$key = array();
	
	// Generate tickets for each ticket they ordered and add to database
	for ($i = 0; $i < $quantity; $i++) {
		$key[$i] = md5(microtime().rand());
		
		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$validation = md5($firstName . ":" . $lastName . ":" . $key[$i]);
		$sql = "INSERT INTO `tickets`(`firstName`,`lastName`,`email`,`phoneNumber`,`paid`,`createdAt`,`onlineOrder`,`paypalTxnId`,`boardDiscount`,`board`,`validation`) VALUES ('" . addslashes($firstName) . "','" . addslashes($lastName) . "','" . addslashes($payer_email) . "','" . $phone . "',1,DATE_SUB(NOW(), INTERVAL 3 HOUR),1,'" . $txn_id ."'," . $boardDiscount . ",'" . addslashes($groupName) . "','" . addslashes($validation) ."')";
		
		if ($conn->query($sql) === FALSE) {
			echo "SQL Error: " . $sql . ": " . $conn->error . "<br/>";
		}
		
		$conn->close();
	}
	
	// Create ticket pdfs and email to payer
	$keyString = implode(',',$key);
	$url = 'https://show.scuvsa.org/tickets/ticket.php';
	$data = array('first' => $firstName, 'last' => $lastName, 'key' => $keyString, 'email' => $payer_email, 'board' => $groupName, 'send' => 1);
	
	$options = array(
		'http' => array(
			'header'	=> "Content-type: application/x-www-form-urlencoded\r\n",
			'method'	=> 'POST',
			'content'	=> http_build_query($data)
		)
	);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE && DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). " Stream Error.");
	}
	
	// Send email to admins alerting of new trx
	$email = new PHPMailer();
	$email->From 		= 'love@scuvsa.org';
	$email->FromName 	= 'SCU VSA';
	$email->Subject		= 'New Transaction Processed';
	$email->Body		= $firstName . " " . $lastName . " (" . $payer_email . ") has purchased " . $quantity . " ticket(s) ($" . $gross / 100 . ", fee: " . $fee . ") to ONE FOOT IN, ONE FOOT OUT.\nSquare Transaction ID: " . $txn_id . ")";
	$email->AddAddress('jfortescue@scu.edu');
	$email->AddAddress('tnle@scu.edu');
	
	$email->Send();
	
	header('Location: https://show.scuvsa.org/completed.php');
	exit;
} catch (\SquareConnect\ApiException $e) {
	echo "Uh-oh! There was an error processing your card. Please contact love@scuvsa.org. Copy and paste the following error output so we can assist you.";
	echo "<pre>";
	print_r($e);
	echo "</pre>";
}
?>