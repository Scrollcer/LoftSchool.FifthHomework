<?php

namespace Base;

use App\Controller\User;

class Application
{
    private $route;
    private $controller;
    private $action;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function run()
    {
        $view = new View();
        $view->setTemplatePath(getcwd() . '/../app/View');
        try {
            $this->route->dispatch($_SERVER['REQUEST_URI']);
            $controller = $this->route->getControllerName();
            $action = $this->route->getActionName();
            $controller->setView($view);

            $session = new Session();
            $session->init();
            $controller->setSession($session);
            $content = $controller->$action();

            echo $content;

        } catch (RedirectException $e) {
            header('Location: ' . $e->getUrl());
            die;
        } catch (RouteException $e) {
            header("HTTP/1.0 404 Not Found");
            echo $e->getMessage();
        }
    }
}