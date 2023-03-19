<?php

namespace Base;

class Route
{
    private $controllerName;
    private $actionName;
    private $routes;

    public function auto($uri)
    {
        $parts = explode('/', $uri);
        if (empty($parts[1])) {
            return false;
        }
        $controllerName = $parts[1];
        $actionName = 'index';
        if (isset($parts[2])) {
            $actionName = $parts[2];
        }
        $controllerClassName = 'App\\Controller\\' . ucfirst(strtolower($controllerName));
        if (!class_exists($controllerClassName)) {
            return false;
        }

        $this->controllerName = new $controllerClassName();
        if (!method_exists($this->controllerName, $actionName)) {
            return false;
        }

        $this->actionName = $actionName;
        return true;
    }

    public function dispatch(string $uri)
    {
        $parsed = parse_url($uri);
        $uri = $parsed['path'];
        if (isset($this->routes[$uri])) {
            $this->controllerName = new $this->routes[$uri][0];
            $this->actionName = $this->routes[$uri][1];
            return;
        }

        if (!$this->auto($uri)) {
            throw new RouteException();
        }
    }

    public function addRoute($path, $controllerName, $actionName = 'index')
    {
        $this->routes[$path] = [
            $controllerName,
            $actionName
        ];
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getActionName()
    {
        return $this->actionName;
    }
}