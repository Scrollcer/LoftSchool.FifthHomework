<?php

namespace Base;

use App\Model\User;

class View
{
    private $templatePath = '';
    private $data = [];
    private $twig;

    public function setTemplatePath(string $path)
    {
        $this->templatePath = $path;
    }

    public function assign($data)
    {

    }

    public function render(string $tpl, $data = []): string
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
        ob_start();
        include $this->templatePath . '/' . $tpl;
        $data = ob_get_clean();
        return $data;
    }

    public function renderTwig(string $tpl, $data = [])
    {
        if (!$this->twig) {
            $loader = new \Twig\Loader\FilesystemLoader($this->templatePath);
            $this->twig = new \Twig\Environment($loader);
        }

        return $this->twig->render($tpl, $data);
    }

    public function __get($varName)
    {
        return $this->data[$varName] ?? null;
    }
}