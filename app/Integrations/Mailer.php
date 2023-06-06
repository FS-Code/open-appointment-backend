<?php

namespace App\Integrations;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use App\Core\Env;

class Mailer
{
    public static function sendMessage(string $to, string $subject, string $message): void
    {
        Env::init();
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = Env::$smtp['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = Env::$smtp['username'];
            $mail->Password   = Env::$smtp['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = Env::$smtp['port'];

            $mail->setFrom(Env::$smtp['from_email'], Env::$smtp['from_name']);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
        } catch (Exception $e) {
            throw new Exception('Email could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
    }
}
