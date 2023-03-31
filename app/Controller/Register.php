<?php

namespace App\Controller;

use App\Model\User;
use Base\AbstractController;
use Illuminate\Database\Eloquent\Model;

class Register extends AbstractController
{
    public function index()
    {
        if ($this->getUser()) {
            $this->redirect('/blog');
        }
        return $this->view->render(
            'register.phtml',
            [
                'title' => 'Главная',
                'user' => $this->getUser(),
            ]
        );
    }

    public function auth()
    {
        $email = (string)$_POST['email'];
        $password = (string)$_POST['password'];

        $user = User::getByEmail($email);
        if (!$user) {
            return 'Неверный логин и пароль';
        }

        if ($user->getPassword() !== User::getPasswordHash($password)) {
            return 'Неверный логин и пароль';
        }

        $this->session->authUser($user->getId());

        $this->redirect('/blog');
    }

    public function register()
    {
        $name = (string)$_POST['name'];
        $email = (string)$_POST['email'];
        $password = (string)$_POST['password'];
        $password2 = (string)$_POST['passwordSecond'];

        if (!$name || !$password) {
            return 'Не заданы имя и пароль';
        }

        if (!$email) {
            return 'Не задан email';
        }

        if ($password !== $password2) {
            return 'Введенные пароли не совпадают';
        }

        if (mb_strlen($password) < 5) {
            return 'Пароль слишком короткий';
        }

        $userData = [
            'name' => $name,
            'created_date' => date('Y-m-d H:i:s'),
            'password' => $password,
            'email' => $email,
        ];


        $user = new User($userData);
        $user->saveUser();


        $this->session->authUser($user->getId());
        $this->redirect('/blog');
    }
}