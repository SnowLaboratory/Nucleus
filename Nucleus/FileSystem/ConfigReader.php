<?php

namespace Nucleus\FileSystem;

use Exception;
use FilesystemIterator;
use SplFileInfo;

use function str_unquote;

class ConfigReader
{
    protected $iterator;

    protected $data;

    protected $contents;

    public function __construct(FilesystemIterator $iterator)
    {
        $this->iterator = $iterator;
        $this->data = [];
    }

    public function get($key, $default=null)
    {
        return data_get($this->data, $key, $default);
    }

    public function load()
    {
        foreach($this->iterator as $path)
        {
            $key = str_before($path->getFilename(), ".");
            $file = config_path($path->getFilename());

            if (!\file_exists($file)) {
                throw new Exception("Cannot find config file [$key]");
            }

            $this->data[$key] = require_once $file;
        }

        return $this;
    }
}