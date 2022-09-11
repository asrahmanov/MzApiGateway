<?php

namespace App\Repositories\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailRepository
{

    public function send()
    {

        $mail = new PHPMailer(true);

        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'mail.pulsarnpp.ru';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'rosel.platform@pulsarnpp.ru';                     //SMTP username
        $mail->Password = 'hStnaP3Nx7';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 25;

        $mail->setFrom('rosel.platform@pulsarnpp.ru', 'Rosel Platform');
        $mail->addAddress('asrahmanov@gmail.com', '');     //Add a recipient
//        $mail->addAddress('ellen@example.com');               //Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');

        //Attachments
//        $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Рассылка от портала';
        $mail->Body = 'Текст письма  <b>in bold!</b>';
        $mail->AltBody = 'Подпись';

        return $mail->send();
    }


}
