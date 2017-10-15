<?php
	$bg = array('img/bgs/ben-thanh.png', 'img/bgs/bitexco-evening.png', 'img/bgs/bitexco-lights.png', 'img/bgs/nha-trang-market.png', 'img/bgs/nha-trang-night.png', 'img/bgs/saigon-long-exposure.png', 'img/bgs/saigon-river.png', 'img/bgs/skyline.png' ); // array of filenames
	
	$i = rand(0, count($bg)-1); // generate random number size of the array
	$selectedBg = "$bg[$i]"; // set variable equal to which random filename was chosen
?>
<html>
<head>
	<title>Thank You! - SCU VSA 2016 Cultural Show</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
	<link href="https://scuvsa.org/css/bootstrap.css" rel="stylesheet" />
	<link href="show.css" rel="stylesheet" />
	<style>
		.side-page-wrapper {
			background-image: url('<? echo $bg[$i] ?>');
		}
	</style>
</head>

<body>
	<?php
		include 'header.php';
		navbar('');
	?>
	<div class="tickets-wrapper">
		<div class="tickets-wrapper-inner">
			<div class="container">
				<h1>Thank you for purchasing your ticket(s) to One Foot In, One Foot Out: 2017 VSA Cultural Show!</h1>
				<h2>You should be receiving an email shortly with your electronic ticket. If you do not receive this email within 24 hours of your purchase, please email us at love@scuvsa.org.</h2>
				<h2>We look forward to seeing you in the audience!</h2>
			</div>
		</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://scuvsa.org/js/bootstrap.min.js"></script>
</body>
</html>