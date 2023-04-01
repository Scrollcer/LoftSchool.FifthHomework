<?php

namespace App\Controller;

use App\Model\User;
use Base\AbstractController;
use Base\RedirectException;

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
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = User::getByEmail($email);
        var_dump($user);
        if (!$user) {
            return 'Неверный логин';
        }

        if ($user->getPassword() !== User::getPasswordHash($password)) {
            var_dump($user->getPassword());
            var_dump(User::getPasswordHash($password));
            die;
            return 'Неверный пароль';
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


        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->created_date = date('Y-m-d H:i:s');
        $user->saveUser();


        $this->session->authUser($user->getId());
        $this->redirect('/blog');
    }

    public function logout()
    {
        $this->session->dropSession();
        $this->redirect('/');
    }
}