<?php

namespace Nucleus\Linting;

use Nucleus;
use Throwable;

class Whoops {

    public function __construct(Nucleus $app)
    {
        error_reporting(E_ALL);

        if ($app->ignoreErrors()) return;
        ini_set("html_errors", "1");
        set_exception_handler([$this, 'handle']);
        // set_error_handler([$this, 'handleErrors']);
    }

    public function parse(Throwable $error)
    {
        $error->getTrace();
    }

    public function simpleErrors()
    {
        ini_set("html_errors", "0");
        restore_exception_handler();
    }

    public function display()
    {

    }

    public function handleErrors( int $code, string $message, ?string $file = null, ?int $line = null, ?array $context = null)
    {
        var_dump(compact('code', 'message', 'file', 'line', 'context')); die;
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