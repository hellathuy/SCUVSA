<?php
require('fpdf.php');
require_once('PHPMailer-master/class.phpmailer.php');

class PDF extends FPDF
{
	// Page header
	function Header()
	{
		global $title;
	}
	
	function CreateTicket($firstName, $lastName, $key, $board) {
		$title = 'ONE FOOT IN, ONE FOOT OUT';
		$this->SetTitle($title);
		$this->SetAuthor("SCU VSA");
		$this->AddPage();
		
		$this->Image('img/ticket.png', 0, 0, $this->GetPageWidth(), $this->GetPageHeight());
		//$this->Output();
		//$this->Image('uprooted.png',0,0,$this->GetPageWidth(),$this->GetPageHeight(),'PNG');
		
		$this->Ln(115);
		$this->SetFont('Arial','B',16);
		$this->Cell(0,10,$firstName . " " . $lastName,0,0,'C');
		$this->Ln(15);
		
		$w = 100;
		$this->Image("https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=http%3A%2F%2Fshow.scuvsa.org%2Ftickets%2Fvalidate.php%3Ffirst%3D" . urlencode(urlencode($firstName)) . "%26last%3D" . urlencode(urlencode($lastName)) . "%26key%3D" . urlencode($key),
			($this->GetPageWidth()-$w)/2,null,$w,0,'PNG');
		$this->Ln();
		
		if ($board != "")
			$this->Cell(0,5,"VSA loves " . $board . "!",0,0,'C');
	}
}

if ($_POST) {
	$firstName = $_POST["first"];
	$lastName = $_POST["last"];
	$board = $_POST["board"];
	$key = $_POST["key"];
	$emailTo = $_POST["email"];
	$send = $_POST["send"];
	
	$keys = explode(',',$key);
	
	$validation = array();
	$pdfs = array();
	for ($i = 0; $i < count($keys); $i++) {
		$validation[$i] = md5($firstName . ':' . $lastName . ':' . $keys[$i]);
		
		$pdfs[$i] = new PDF('P','mm','Letter');
		$pdfs[$i]->CreateTicket($firstName,$lastName,$keys[$i],$board);
	}
	
	if ($emailTo == "") {
		$pdfs[0]->Output('F','archive/' . $validation[0] . '.pdf','true');
		$pdfs[0]->Output('D',$firstName . '-' . $lastName . '-Ticket.pdf');
	} else {
		$email = new PHPMailer();
		$email->From 		= 'love@scuvsa.org';
		$email->FromName 	= 'SCU VSA';
		$email->Subject		= 'Your ticket to ONE FOOT IN, ONE FOOT OUT';
		ob_start();
		include('email.php');
		$email->Body		= ob_get_contents();
		ob_end_clean();
		$email->IsHTML(true);
		$email->AddAddress($emailTo);
		
		for ($i = 0; $i < count($pdfs); $i++) {
			$pdfs[$i]->Output('F','archive/' . $validation[$i] . '.pdf','true');
			$file_to_attach = 'archive/' . $validation[$i] . '.pdf';
			$email->AddAttachment($file_to_attach, $firstName . '-' . $lastName . '-Ticket-' . $i . '.pdf');
		}
		
		if ($send != 0)
		{
			if ($email->Send())
				echo "Email successfully sent to " . $emailTo;
			else
				echo "Email failed to send!";
		}
	}
} else {
	echo "<h1>Ticket Invalid</h1>";
}
?>