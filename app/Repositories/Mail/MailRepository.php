<?php

namespace App\Repositories\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailRepository
{

    public function send($subject, $text, $email)
    {

        $mail = new PHPMailer(true);

        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'mail.pulsarnpp.ru';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'rosel.platform@pulsarnpp.ru';                     //SMTP username
        $mail->Password = 'hStnaP3Nx7';                               //SMTP password
        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = false;
        $mail->Port = 25;
        $mail->CharSet = 'utf-8';

        $mail->setFrom('rosel.platform@pulsarnpp.ru', 'Rosel Platform');
        $mail->addAddress($email, '');     //Add a recipient
//        $mail->addAddress('ellen@example.com');               //Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');

        //Attachments
//        $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $text;
//        $mail->AltBody = 'Подпись';

        return $mail->send();
    }


}
