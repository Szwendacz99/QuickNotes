<?php

class AppController {

    private string $request;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
    }

    protected function isGet(): bool
    {
        return $this->request === 'GET';
    }

    protected function isPost(): bool
    {
        return $this->request === 'POST';
    }

    protected function render(string $template = null, array $variables = []): void
    {
        $templatePath = 'public/views/'. $template.'.php';
        $output = 'File not found';

        if(file_exists($templatePath)){
            extract($variables, EXTR_OVERWRITE);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }
        print $output;
    }
}
