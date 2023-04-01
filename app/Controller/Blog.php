<?php

namespace App\Controller;

use App\Model\Message;
use App\Model\User;
use Base\AbstractController;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Intervention\Image\ImageManager;

class Blog extends AbstractController
{
    public function index()
    {
        if (!$this->getUser()) {
            $this->redirect('/register');
        }
        $messages = Message::getList();

        return $this->view->render('blog.phtml', [
            'messages' => $messages,
            'user' => $this->getUser()
        ]);
    }

    public function addMessage()
    {
        if (!$this->getUser()) {
            $this->redirect('/register');
        }

        $text = $_POST['text'];
        if (!$text) {
            $this->error('Сообщение не может быть пустым');
        }

        $message = new Message();

        if (isset($_FILES['image']['tmp_name'])) {
            $message->loadFile($_FILES['image']['tmp_name']);
        }

        $message->text = $text;
        $message->author_id = $this->getUserId();
        $message->created_date = date('Y-m-d H:i:s');
        $message->saveMessage();

        $this->redirect('/blog');

    }

    public function twig()
    {
        return $this->view->renderTwig('twig.twig', ['var' => 'Hello!']);
    }

    public function sendMail()
    {
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
    }


    public function image()
    {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'images/';

        $source = $imagePath . 'test.png';
        $result = $imagePath . 'test_new.png';
        $image = (new ImageManager)->make($source)
            ->resize(200, null, function ($image) {
                $image->aspectRatio();
            })
            ->save($result, 100);

        $image->text(
            "Heroes 5: Demon",
            $image->width() / 2,
            $image->height() / 2,
            function ($font) {
                $font->color(array(255, 255, 255, 0.5));
                $font->align('left');
                $font->valign('center');
            }
        );

        echo $image->response('png');
    }

    private function error()
    {

    }
}