<?php

require_once '../vendor/autoload.php';

try {
    // Create the Transport
    $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
        ->setUsername('sergalazz@mail.ru')
        ->setPassword('QrFNs6dtnhjbZiAdWSTE');

// Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

// Create a message
    $message = (new Swift_Message('Wonderful Subject'))
        ->setFrom(['sergalazz@mail.ru' => 'sergalazz@mail.ru'])
        ->setTo(['denslaz@mail.ru'])
        ->setBody('Hello!');

// Send the message
    $result = $mailer->send($message);
    var_dump(['res' => $result]);
} catch (Exception $e) {
    echo 'ERROR!';
    echo '<pre>' . print_r($e->getTrace(), 1);
}