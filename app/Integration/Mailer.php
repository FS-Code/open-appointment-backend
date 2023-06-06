<?php
namespace Integrations;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer {
    public static function sendMessage(string $to, string $subject, string $message): void {
        require 'path/to/PHPMailer/src/PHPMailer.php';
        require 'path/to/PHPMailer/src/SMTP.php';
        require 'path/to/PHPMailer/src/Exception.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com'; 
            $mail->Password = 'your-password'; 
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587; 

            
            $mail->setFrom('your-email@example.com', 'Your Name'); 
            $mail->addAddress($to); 

            
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            
            $mail->send();
        } catch (Exception $e) {
            throw new \Exception('Message could not be sent. PHPMailer Error: ' . $e->getMessage());
        }
    }
}
