<?php
include '../smtp/src/Exception.php';
include '../smtp/src/PHPMailer.php';
include '../smtp/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function send_email($host,$username,$password,$port,$setfrom,$mailaddr,$subject,$body){
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 1;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = $host;                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $username;                     // SMTP username
    $mail->Password   = $password;                               // SMTP password
    $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = $port;
    $mail->setFrom($setfrom, $setfrom);
    $mail->addAddress($mailaddr);     // Add a recipient
    $mail->Charset='UTF-8';
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->send();
    return $body;
}
