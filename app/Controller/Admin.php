<?php

namespace App\Controller;

use App\Model\Message;
use App\Model\User;
use Base\AbstractController;

class Admin extends AbstractController
{
    public function saveUser()
    {
        $id = $_POST['id'];
        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        $user = User::getById($id);
        if (!($name && $email && $password)) {
            echo "Введите все данные!";
        }

        $user->name = $name;
        $user->email = $email;
        $user->password = User::getPasswordHash($password);
        $user->save();

    }

    public function deleteUser()
    {
        $id = (int)$_POST['id'];

        $user = User::getById($id);
        $user->delete();
    }

    public function addUser()
    {
        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!($name && $email && $password)) {
            echo "Введите все данные!";
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->created_date = date('Y-m-d H:i:s');
        $user->saveUser();

    }

    public function index()
    {
        if (!$this->getUser() || !$this->getUser()->isAdmin()) {
            $this->redirect('/');
        }
        return $this->view->render('admin.phtml', ['users' => User::getList()]);
    }

    public function preDispatch()
    {
        parent::preDispatch();
        if (!$this->getUser() || !$this->getUser()->isAdmin()) {
            $this->redirect('/');
        }
    }

    public function deleteMessage()
    {
        $messageId = (int)$_GET['id'];
        Message::deleteMessage($messageId);
        $this->redirect('/blog');
    }
}