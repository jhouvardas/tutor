<?php
defined('PHPMAILER') || die('Direct access to this file is not permitted.');

if(!class_exists('PHPMailer')){
	require 'class.phpmailer.php';
	require 'class.smtp.php';
}

$mail = new PHPMailer;

$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->CharSet  = 'utf-8';
$mail->isHTML(true);

require 'credentials.php';
