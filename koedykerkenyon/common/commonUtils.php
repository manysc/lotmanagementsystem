<?php

include dirname(__FILE__) . '/../libraries/PHPMailer/class.phpmailer.php';

$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
$firefox = strpos($_SERVER["HTTP_USER_AGENT"], 'Firefox') ? true : false;
$safari = strpos($_SERVER["HTTP_USER_AGENT"], 'Safari') ? true : false;
$chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;

define("WEEK_HOURS_FOR_NORMAL_WAGE", 40);
define("OVERTIME_RATE", 1.5);

if(isset($_POST['logout'])) {
	session_unset();
	$url = 'index.php';
	echo "<script>window.location='" . $url . "'</script>";
}

function sendEmail($message) {
	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'koedykerkenyon';                            // SMTP username
	$mail->Password = 'koedykerkenyon1';                           // SMTP password
	$mail->SMTPSecure = 'tls';
	$mail->Port = '587';                        // Enable encryption, 'ssl' also accepted
	$mail->From = 'koedykerkenyon@gmail.com';
	$mail->FromName = 'Koedyker & Kenyon';
	
	$mail->addAddress('elchapito7@hotmail.com', 'Martin Pesqueira Mendoza');  // Add a recipient
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'LotManagement Automated Email';
	$mail->Body    = $message;

	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		return false;
	}
	return true;
}

function sendEmailTo($message, $email, $recipient) {
	$mail = new PHPMailer;
	$mail->isSMTP(); // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true; // Enable SMTP authentication
	$mail->Username = 'koedykerkenyon'; // SMTP username
	$mail->Password = 'koedykerkenyon1'; // SMTP password
	$mail->SMTPSecure = 'tls';
	$mail->Port = '587'; // Enable encryption, 'ssl' also accepted
	$mail->From = 'koedykerkenyon@gmail.com';
	$mail->FromName = 'Koedyker & Kenyon';
	$mail->addAddress($email, $recipient);  // Add a recipient
	$mail->isHTML(true); // Set email format to HTML

	$mail->Subject = 'LotManagement Automated Email';
	$mail->Body    = $message;
	
	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		return false;
	}
	return true;
}

?>