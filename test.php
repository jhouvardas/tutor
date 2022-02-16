<?php

define ("PHPMAILER", true);
require 'phpmailer/PHPMailerAutoload.php';
include('phpmailer/config.inc.php');

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
$mail->addAddress('jhouvardas@gmail.com', 'yannis');
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}