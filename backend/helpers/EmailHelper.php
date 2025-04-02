<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;






require_once __DIR__ . '/PHPMailer/src/Exception.php';

require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';

require_once __DIR__ . '/PHPMailer/src/SMTP.php';


/**
 * Email Helper Class
 * Handles sending various emails for the application using PHPMailer
 */
class EmailHelper
{
    private $fromEmail;
    private $fromName;
    private $smtpHost;
    private $smtpPort;
    private $smtpUsername;
    private $smtpPassword;

    public function __construct()
    {
        // Load email configuration (hardcoded for now; use env/config file in production)
        $this->fromEmail = 'no-reply@axisforexbot.com'; // Update with your "From" email address
        $this->fromName = 'AxisBot';
        $this->smtpHost = 'mail.axisforexbot.com';      // SMTP host
        $this->smtpPort = 587;                         // SMTP port
        $this->smtpUsername = 'no-reply@axisforexbot.com'; // SMTP username
        $this->smtpPassword = 'u8zxNPuGy8Dx9nM';        // SMTP password
    }

    /**
     * Send welcome email to new users
     * 
     * @param string $email User email address
     * @param string $firstName User's first name
     * @return boolean True if email sent successfully
     */
    public function sendWelcomeEmail($email, $firstName)
    {
        $subject = 'Welcome to AxisBot!';
        $message = "
        <html>
        <head>
            <title>Welcome to AxisBot</title>
        </head>
        <body>
            <h2>Welcome to AxisBot, {$firstName}!</h2>
            <p>Thank you for joining our platform. We're excited to have you on board!</p>
            <p>Here's what you can do with your account:</p>
            <ul>
                <li>Access your personalized dashboard</li>
                <li>Track your investments</li>
                <li>Connect with other investors</li>
                <li>Stay updated with market insights</li>
            </ul>
            <p>If you have any questions or need assistance, feel free to contact our support team.</p>
            <br>
            <p>Best regards,<br>The AxisBot Team</p>
        </body>
        </html>";

        return $this->sendEmail($email, $subject, $message);
    }

    /**
     * Send verification email
     */
    public function sendVerificationEmail($email, $token)
    {
        $subject = 'Verify your AxisBot account';
        $verificationLink = "https://axisbot.com/backend/views/verify.php?token=" . $token;

        $message = "
        <html>
        <head>
            <title>Verify your AxisBot account</title>
        </head>
        <body>
            <h2>Welcome to AxisBot!</h2>
            <p>Please click the link below to verify your email address:</p>
            <p><a href='{$verificationLink}'>{$verificationLink}</a></p>
            <p>This link will expire in 24 hours.</p>
            <p>If you didn't create an account, you can safely ignore this email.</p>
            <br>
            <p>Best regards,<br>The AxisBot Team</p>
        </body>
        </html>";

        return $this->sendEmail($email, $subject, $message);
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($email, $token)
    {
        $subject = 'Reset your AxisBot password';
        $resetLink = "https://axisbot.com/backend/views/reset-password.php?token=" . $token;

        $message = "
        <html>
        <head>
            <title>Reset your AxisBot password</title>
        </head>
        <body>
            <h2>Password Reset Request</h2>
            <p>Click the link below to create a new password:</p>
            <p><a href='{$resetLink}'>{$resetLink}</a></p>
            <p>This link will expire in 1 hour.</p>
            <p>If you didn't request a password reset, you can safely ignore this email.</p>
            <br>
            <p>Best regards,<br>The AxisBot Team</p>
        </body>
        </html>";

        return $this->sendEmail($email, $subject, $message);
    }

    /**
     * Core function to send email using PHPMailer
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message HTML email body
     * @return boolean True if email sent successfully
     */
    private function sendEmail($to, $subject, $message)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption
            $mail->Port = $this->smtpPort;

            // Recipients
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($to);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Email could not be sent. Error: ' . $mail->ErrorInfo;
            return false;
        }
    }
}
