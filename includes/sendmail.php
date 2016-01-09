<?php

require_once "Mail.php";
function sendmail($to,$mess){
$from = '<fhswege3@gmail.com>';
$subject = 'Ihre neues Passwort für Quick Deal';
$body = "Ihre neue Passwort lautet: ".$mess;

$headers = array(
    'From' => $from,
    'To' => $to,
    'Subject' => $subject
);

$smtp = Mail::factory('smtp', array(
        'host' => 'ssl://smtp.gmail.com',
        'port' => '465',
        'auth' => true,
        'username' => 'fhswege3@gmail.com',
        'password' => '23146758'
    ));

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
    echo('<p>' . $mail->getMessage() . '</p>');
} else {
 //   echo('<p>Message successfully sent!</p>');
}
}
?>
