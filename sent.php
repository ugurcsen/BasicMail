<?php
$data = [
    'Host' => 'smtp1.example.com', //SMTP host address
    'Name' => '', //Sender Name
    'Mail' => 'user@example.com', //Sender mail
    'Password' => '*******', //Sender password
    'Port' => 587, //SMTP port (Only change if you have to)
    'ReciverMails' => [
        'mail@example.com'
    ], //Reciver Mails (It must be array)
    'Subject' => 'Example mail subject', //Mail subject
    'Body' => createTableFromForm(), //Mail body
    'AltBody' => '', //Mail alt body same as signature
    'Attachments' => [

    ] //Attachments locations if you need (must be array)
];

function done() //This is what is going to do when mail sent
{
    echo 'Message has been sent';
}


require './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host = $data['Host'];                    // Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    $mail->Username = $data['Mail'];                     // SMTP username
    $mail->Password = $data['Password'];                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom($data['Mail'], $data['Name']);
    foreach ($data['ReciverMails'] as $reciverMail) {
        $mail->addAddress($reciverMail);
    }

    // Attachments
    foreach ($data['Attachments'] as $attachment) {
        $mail->addAttachment($attachment);// Add attachments
    }

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $data['Subject'];
    $mail->Body = $data['Body'];
    $mail->AltBody = $data['AltBody'];

    $mail->send();
    done();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

function createTableFromForm() //Table create from form to sent with mail
{
    $text = '<table border=1>';
    foreach ($_POST as $key => $val) {
        $text .= "<tr><td>{$key}</td><td>{$val}</td></tr>";
    }
    $text .= '<tr><td>Date</td><td>' . date("Y-m-d H:i:s") . '</td></tr>';
    $text .= '</table>';
    return $text;
}
