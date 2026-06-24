<?php

class MailMaker
{

    public function sendMail($body, $email, $subject = 'Ασκήσεις')
    {
        define("PHPMAILER", true);
        require_once 'phpmailer/PHPMailerAutoload.php';
        include_once 'phpmailer/config.inc.php';
        $name = 'Γιάννης';
        $mail->Subject = $subject;
        $mail->Body = $body;
        //        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->addAddress($email, $name);
        $mail->addAddress("jhouvardas@jhouv.eu", $name);
        $mail->addAddress("ioannishouvardas@gmail.com", $name);
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }

    public function sendBulkGroupMails(array $students, string $subject, string $groupName, string $messageHtml): array
    {
        if (!defined("PHPMAILER")) define("PHPMAILER", true);
        require_once 'phpmailer/PHPMailerAutoload.php';
        include 'phpmailer/config.inc.php';
        $mail->SMTPKeepAlive = true;

        $successful = [];
        $failed = [];

        foreach ($students as $student) {
            if (empty($student['email'])) {
                $failed[] = ['name' => $student['name'] . ' ' . $student['lastName'], 'email' => 'Δεν υπάρχει email', 'error' => 'Δεν έχει δηλωθεί email.'];
                continue;
            }
            $body = "<div style='font-family:Arial,sans-serif;max-width:600px;padding:20px;border:1px solid #eee;border-radius:10px;'>
                <h2 style='color:#007bff;'>Ενημέρωση: " . htmlspecialchars($groupName) . "</h2>
                <p>Γεια σου <b>" . htmlspecialchars($student['name']) . "</b>,</p>
                <div style='padding:15px;background:#f8f9fa;border-radius:5px;margin-bottom:20px;'>$messageHtml</div>
                <p>Καλή συνέχεια!</p></div>";
            $mail->clearAddresses();
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->isHTML(true);
            $mail->addAddress($student['email']);
            if ($mail->send()) {
                $successful[] = ['name' => $student['name'] . ' ' . $student['lastName'], 'email' => $student['email']];
            } else {
                $failed[] = ['name' => $student['name'] . ' ' . $student['lastName'], 'email' => $student['email'], 'error' => 'Σφάλμα αποστολής'];
            }
        }
        $mail->smtpClose();
        return ['successful' => $successful, 'failed' => $failed];
    }

    public function makeErgasiaEmail($arrayOfAskiseis, $name)
    {
        $mail = '<!DOCTYPE html>';
        $mail .= '<html>';
        $mail .= '<head>';
        $mail .= '<meta charset="utf-8">';
        $mail .= '<style>';
        $mail .= 'h1 {';
        $mail .= 'color: maroon;';
        $mail .= 'margin-left: 40px;';
        $mail .= '}';
        $mail .= '</style>';
        $mail .= '</head>';
        $mail .= '<body>';
        $mail .= '<h1>Εργασία</h1>';
        $mail .= '<div>';
        $mail .= '<table  style="border-collapse: collapse; width: 250px; text-align: left;font-family: Arial, Helvetica, sans-serif;background-color:LightGray; "> ';
        $mail .= '<thead style ="background-color: lightgreen; padding: 15px;">';
        $date = date_create($_SESSION['date']);
        $mail .= '<tr><th style=" padding: 15px;">Ασκήσεις</th><td>';
        $mail .= date_format($date, " d/m/y");
        $mail .= '</td></tr>';
        $mail .= '<tr><th style=" padding: 15px;">Όνομα</th><td>' . $name . '</td></tr>';
        $mail .= '<tr><th style=" padding: 15px;">Τοποθεσία</th><td>' . $_SESSION['location'] . '</td></tr>';
        $mail .= '<tr><th style=" padding: 15px;">Βιβλίο</th><td>' . $_SESSION['askiseisSource'] . '</td></tr>';
        $mail .= '</thead>';
        $mail .= '<tr style="background-color:LightGray; "><th style=" padding: 15px;">α/α</th><th>Άσκηση</th></tr>';
        $mail .= '<tbody style="background-color:LightGray; ">';
        $arrlength = count($arrayOfAskiseis);
        for ($i = 0; $i < $arrlength; $i++) {
            $mail .= '<tr>';
            $mail .= '<td style=" padding: 15px;">' . ($i + 1) . '</td><td>' . $arrayOfAskiseis[$i] . '</td>';
            $mail .= '</tr>';
        }
        $mail .= '<tr><th style="text-align: center; background-color: lightgreen;"  colspan="2"><p>Καλό διάβασμα</p></th></tr>';
        $mail .= '</tbody>';
        $mail .= '</table>';
        $mail .= '</div>';
        $mail .= '</body>';
        $mail .= '</html>';
        return $mail;
    }


}
