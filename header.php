<?php

function navbar($username) {
	?>
	<div class="container-fluid">
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="https://scuvsa.org""><img src="https://scuvsa.org/img/SCUVSAlogohighres.png" title="SCU VSA"></a>
				</div>
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="https://scuvsa.org">Home</a><span class="sr-only"> (current)</span></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My VSA <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<?
								if (empty($username)) {
									?>
									<li><a href="">Login</a></li>
									<li><a href="https://scuvsa.org/myvsa/register.php">Register</a></li>
									<?
								}	
								else {
									?><li><a href="">My Account</a></li><?
								}
								?>
							</ul>
						</li>
					</ul>
					<div class="social-bar">
						<ul id="test" class="nav navbar-nav">
							<li class="social-bar-fb"><a href="https://www.facebook.com/scuvsalove/" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
							<li class="social-bar-ig"><a href="https://www.instagram.com/scu_vsa/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></i></a></li>
							<li class="social-bar-yt"><a href="https://www.youtube.com/user/SantaClaraVSA" target="_blank"><i class="fa fa-youtube-square" aria-hidden="true"></i></i></a></li>
						</ul>
					</div>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="https://scuvsa.org/about">About</a></li>
						<li><a href="https://scuvsa.org/board">Board</a></li>
						<li class="active"><a href="https://show.scuvsa.org">Show</a></li>
						<li><a href="https://scuvsa.org/calendar">Calendar</a></li>
						<!-- <li><a href="https://scuvsa.org/history">History</a></li> -->
					</ul>
				</div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</nav>
	</div>
	<?
}

?>