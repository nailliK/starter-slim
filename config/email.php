<?php
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = 'smtp.mandrillapp.com';
$mail->SMTPAuth = true;
$mail->Username = 'you@you.com';
$mail->Password = 'password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->isHTML(true);
$mail->setFrom('no-reply@myurl.com', 'No-Reply');