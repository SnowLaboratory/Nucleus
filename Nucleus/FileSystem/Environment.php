<?php

namespace Nucleus\FileSystem;

use Exception;

use function str_unquote;

class Environment
{
    protected $path;

    protected $data;

    protected $contents;

    public function __construct($path)
    {
        $this->path = $path;
        $this->data = [];
    }

    protected function unquote($str, $quotes="'")
    {
        return rtrim(ltrim($str, $quotes), $quotes);
    }

    protected function parse()
    {   
        $lines = explode(PHP_EOL, $this->contents);

        foreach($lines as $line)
        {
            if (!$this->line_is_valid($line)) continue;
            $tokens = explode('=', $line, 2);
			$entry = array_combine(["key", "value"], $tokens);

            $key = $this->unquote(str_quoted($entry['key']));
            $value = $this->unquote(str_quoted($entry['value']));

			$this->data[$key] = $value;
        }

        return $this;
    }

    public function get($key, $default=null)
    {
        return data_get($this->data, $key, $default);
    }

    protected function line_is_valid($line)
    {
        return !empty(trim($line)) && str_contains($line, '=');
    }

    public function load()
    {
        if (!file_exists($this->path))
        {
            $path = normalize_path($this->path);
            throw new Exception("Could not find environment file [$path]");
        }
        
        $this->contents = file_get_contents($this->path);

        $this->parse();

        return $this;
    }
}