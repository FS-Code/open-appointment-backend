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
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Update with your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com'; // Update with your email address
            $mail->Password = 'your-password'; // Update with your email password
            $mail->SMTPSecure = 'tls'; // TLS encryption, you can use 'ssl' if needed
            $mail->Port = 587; // SMTP port, update if needed

            // Sender and recipient
            $mail->setFrom('your-email@example.com', 'Your Name'); // Update with your email and name
            $mail->addAddress($to); // Add recipient email address

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Send the email
            $mail->send();
        } catch (Exception $e) {
            // Exception occurred, throw an exception with the error message
            throw new \Exception('Message could not be sent. PHPMailer Error: ' . $e->getMessage());
        }
    }
}
