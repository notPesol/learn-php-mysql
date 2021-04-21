<?php
    /*  
    Import PHPMailer classes into the global namespace
    These must be at the top of your script, not inside a function
    */
    
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
$mail->CharSet ='utf-8';

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Mailer = 'smtp';
    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'pesol2012@gmail.com';                  //SMTP username
    $mail->Password   = 'ivgqainqcvptgwbv';                     //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('admin@example.com', 'Admin Mailer');
    $mail->addAddress('pesol2012@hotmail.com', 'To firstname user');     //Add a recipient
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


    $code = strval(mt_rand(100000, 999999));
    $mail->send();
    echo 'Message has been sent' . "$code";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}