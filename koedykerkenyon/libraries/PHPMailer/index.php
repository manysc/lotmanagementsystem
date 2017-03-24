<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
CORREO
</body>
</html>

<?php
//echo phpinfo();
   // mail('renesc@gmail.com','Hello','Testing Testing','From:renesc@gmail.com')or die();
?>


<?php
require 'class.phpmailer.php';

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';
//smtp2.example.com';  // Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'renesc';                            // SMTP username
$mail->Password = 'Naranjeros23';                           // SMTP password
$mail->SMTPSecure = 'tls';    
$mail->Port = '587';                        // Enable encryption, 'ssl' also accepted

$mail->From = 'rene@gmail.com';
$mail->FromName = 'Rene Salas';
$mail->addAddress('manuel.salasc@gmail.com', 'Manuel Salas');  // Add a recipient
$mail->addAddress('renesc@gmail.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Prueba de correo para Manuel Salas Cardenas';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
$mail->SMTPDebug=1;
if(!$mail->send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo 'Message has been sent';

?>

