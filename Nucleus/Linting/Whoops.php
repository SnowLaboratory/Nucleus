<?php

namespace Nucleus\Linting;

use Throwable;

class Whoops {

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set("html_errors", "1");
        set_error_handler([$this, 'error']);
        set_exception_handler([$this, 'handle']);
    }

    public function parse(Throwable $error)
    {
        $error->getTrace();
    }

    public function display()
    {

    }

    public function error($code, $message, $file, $line)
    {
        var_dump(compact('code', 'message', 'file', 'line')); die;
    }

    public function handle($error)
    {
        extract(compact('error'));
        ob_start();
        require internal_path('Linting/error.view.php');
        $content = ob_get_clean();
        echo $content;
        exit();
    }
}