<?php 
//this code for sending mail is directly taken from swift mailer documentation and made changes accordingly
require_once 'vendor/autoload.php';
require_once 'config/constants.php';
// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
  ->setUsername(EMAIL)
  ->setPassword(PASSWORD)
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);



function sendVerificationEmail($userEmail,$token)
{   
    //to access mailer object inside our function we will declare it globally
    global $mailer;

    //define variable body
    $body= '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Verify email</title>
            </head>
            <body>
                <div class="wrapper">
                    <p>
                        Thank you for signing up to our website. Please click on the link below
                        to verify your email!!
                    </p>
                    <a href="http://localhost/login%20signup%20system/index.php?token= ' . $token . ' ">
                    Verify Your Email Address</a>
                </div>
            </body>
            </html>';
    // Create a message
    $message = (new Swift_Message('Verify your email address'))
    ->setFrom(EMAIL)
    ->setTo($userEmail)
    //now we will send body with the link in the form html so we cant send normal text message we have to modify it
    ->setBody($body, 'text/html')
    ;

    // Send the message
    $result = $mailer->send($message);
}

function sendPasswordResetLink($userEmail,$token){
     //to access mailer object inside our function we will declare it globally
     global $mailer;

     //define variable body
     $body= '<!DOCTYPE html>
             <html lang="en">
             <head>
                 <meta charset="UTF-8">
                 <title>Verify email</title>
             </head>
             <body>
                 <div class="wrapper">
                     <p>
                         Hello there,
                         Please click on the link below to reset your password.
                     </p>
                     <a href="http://localhost/login%20signup%20system/index.php?password-token= ' . $token . ' ">
                     Reset your password</a>
                 </div>
             </body>
             </html>';
     // Create a message
     $message = (new Swift_Message('Reset your password'))
     ->setFrom(EMAIL)
     ->setTo($userEmail)
     //now we will send body with the link in the form html so we cant send normal text message we have to modify it
     ->setBody($body, 'text/html')
     ;
 
     // Send the message
     $result = $mailer->send($message);
}



?>