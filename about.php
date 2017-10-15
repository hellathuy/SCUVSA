<?php
	$bg = array('img/bgs/ben-thanh.png', 'img/bgs/bitexco-evening.png', 'img/bgs/bitexco-lights.png', 'img/bgs/nha-trang-market.png', 'img/bgs/nha-trang-night.png', 'img/bgs/saigon-long-exposure.png', 'img/bgs/saigon-river.png', 'img/bgs/skyline.png' ); // array of filenames
	
	$i = rand(0, count($bg)-1); // generate random number size of the array
	$selectedBg = "$bg[$i]"; // set variable equal to which random filename was chosen
?>
<html>
<head>
	<title>About the Show - SCU VSA 2016 Cultural Show</title>
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
	<div class="site-wrapper">
		<div class="site-wrapper-inner side-page-wrapper">
			<div class="container">
				<div class="about-title">UPROOTED</div>
				<div class="about-subtitle">Follow the board members of the Santa Clarita Vietnamese Student Association on a trip to Vietnam!</div>
				<div class="about-desc">
					Seeking inspiration for their upcoming cultural show, Julie Ly, Lauren Johnson, Dan Dang, and Ross Do set out for the streets of Saigon to better understand true Vietnamese culture.
					Soon, though, tensions arise as their identities as Vietnamese-Americans are called into question.
				</div>
				<div>
					<a href="buytickets.php"><button class="btn btn-lg btn-black">Buy Tickets Now!</button></a>
				</div>
				<div class="about-stars">
					<div class="about-star-headline">STARRING</div>
					<div class="col-md-3 col-sm-6 about-star">
						<img class="img-circle about-star-headshot" src="https://scuvsa.org/board/img/headshots/mimi.jpg">
						<div class="about-star-caption"><span class="about-star-name">Mimi Pham</span> as <span class="about-star-role">Julie Ly</span></div>
					</div>
					<div class="col-md-3 col-sm-6 about-star">
						<img class="img-circle about-star-headshot" src="https://scuvsa.org/board/img/headshots/diane.jpg">
						<div class="about-star-caption"><span class="about-star-name">Diane Hoang</span> as <span class="about-star-role">Lauren Johnson</span></div>
					</div>
					<div class="col-md-3 col-sm-6 about-star">
						<img class="img-circle about-star-headshot" src="https://scuvsa.org/board/img/headshots/vincent.jpg">
						<div class="about-star-caption"><span class="about-star-name">Vincent Pham</span> as <span class="about-star-role">Dan Dang</span></div>
					</div>
					<div class="col-md-3 col-sm-6 about-star">
						<img class="img-circle about-star-headshot" src="https://scuvsa.org/board/img/headshots/bryan.jpg">
						<div class="about-star-caption"><span class="about-star-name">Bryan Ton</span> as <span class="about-star-role">Ross Do</span></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://scuvsa.org/js/bootstrap.min.js"></script>
</body>
</html>