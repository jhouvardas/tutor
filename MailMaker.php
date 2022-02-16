<?php

class MailMaker {

    public function sendMail($body, $email) {
        define("PHPMAILER", true);
        require 'phpmailer/PHPMailerAutoload.php';
        include('phpmailer/config.inc.php');
        $name = 'Γιάννης';
        $mail->Subject = 'Ασκήσεις';
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

    public function makeErgasiaEmail($arrayOfAskiseis, $name) {
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

   

    public function makePanelliniesEmail($panelliniesResource) {
        $panelliniesMail = '<!DOCTYPE html>';
        $panelliniesMail .= '<html>';
        $panelliniesMail .= '<head>';
        $panelliniesMail .= '<meta charset="utf-8">';
        $panelliniesMail .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
        $panelliniesMail .= '</head>';
        $panelliniesMail .= '<body>';
        $panelliniesMail .= '<h1>Εργασία</h1>';
        $panelliniesMail .= '<table  style="border-collapse: collapse; width: 250px; text-align: left;font-family: Arial, Helvetica, sans-serif;background-color:LightGray; "> ';
        $panelliniesMail .= '<thead style ="background-color: lightgreen; padding: 15px;">';
        $panelliniesMail .= '<tr>';
        $panelliniesMail .= '<th></th>';
        $panelliniesMail .= '<th>Ημερ.</th>';
        $panelliniesMail .= '<th>Όνομα</th>';
        $panelliniesMail .= '<th>Χρον</th>';
        $panelliniesMail .= '<th>Λύκειο</th>';
        $panelliniesMail .= '<th>Θέμ</th>';
        $panelliniesMail .= '<th>Ερώτ</th>';
        $panelliniesMail .= '<th></th>';
        $panelliniesMail .= '</tr>';
        $panelliniesMail .= '</thead>';
        $panelliniesMail .= '<tbody style="background-color:LightGray; ">';
        $i = 1;
        reset($row);
        while ($row = $panelliniesResource->fetch_assoc()) {
            $panelliniesMail .= '<tr>';
            $panelliniesMail .= '<td>' . $i . '</td>';
            $date = date_create($row['date']);
            $panelliniesMail .= '<td>' . date_format($date, " d/m/y") . '</td>';
            $panelliniesMail .= '<td>' . $row['name'] . '</td>';
            $panelliniesMail .= '<td>' . $row['panelliniesYear'] . '</td>';
            $panelliniesMail .= '<td>' . $row['lykeio'] . '</td>';
            $panelliniesMail .= '<td>' . $row['thema'] . '</td>';
            $panelliniesMail .= '<td>' . $row['erotima'] . '</td>';
            $panelliniesMail .= '<td>' . $row['location'] . '</td>';
            $panelliniesMail .= '</tr>';
            $i++;
        }
        $panelliniesMail .= '</tbody>';
        $panelliniesMail .= '</table>';
        $panelliniesMail .= '</div>';
        $panelliniesMail .= '</body>';
        $panelliniesMail .= '</html>';
        echo $panelliniesMail;
        return $panelliniesMail;
    }

}
