<?php

use Nucleus\Foundation\Container;
use Nucleus\Linting\Whoops;
use Nucleus\Providers\ConfigServiceProvider;
use Nucleus\Providers\EnvironmentServiceProvider;
use Nucleus\Providers\ErrorServiceProvider;
use Nucleus\Providers\ServiceProvider;

class Nucleus
{
    protected $container;

    protected $services = [];

    protected $instances = [];

    protected $linter;

    private static $apps;

    private static $count = 0;

    private $id;

    public function __construct()
    {
        $this->id = static::$count++;
        static::$apps[] = $this;
        $this->containerize();
        self::registerInternalServices();
        self::registerExternalServices();
    }

    protected function autoload ($class)
    {
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        if (str_starts_with($class, 'Nucleus')) {
            $class = str_after($class, 'Nucleus/');
            return require_once internal_path(implode('.', [$class, 'php']));
        }

        require_once base_path(implode('.', [$class, 'php']));
    }

    public function containerize() {
        spl_autoload_register([$this, 'autoload']);

        $this->container = new Container;

        require internal_path('Helpers/container.php');
    }

    protected function registerInternalServices()
    {
        $this->register(ErrorServiceProvider::class);
        $this->register(EnvironmentServiceProvider::class);
        $this->register(ConfigServiceProvider::class);
    }

    protected function registerExternalServices()
    {
        $providers = config('app.providers');
        foreach ($providers as $provider) {
            $this->register($provider);
        }
    }

    public function require($path)
    {
        if (!file_exists($path)) {
            throw new Exception("Cannot load path [$path]");
        }

        require $path;
    }

    public function register($provider)
    {
        if (!is_a($provider, ServiceProvider::class, true)) {
            throw new Exception('Registered object is not a ServiceProvider');
        }

        $this->services[] = $provider;

        $this->bind($provider, fn() => new $provider($this));
        $this->resolve($provider)->register();
    }

    public function bind(string $key, callable $resolver) 
    {
        try {
            $this->container->bind($key, $resolver);
        } catch (Throwable $error) {
            $whoops = $this->resolve(Whoops::class);
            $whoops->parse($error);
            $whoops->display();
        }
    }

    public function singleton(string $key, callable $resolver)
    {
        $this->bind($key, $resolver);
        $this->instances[$key] = $this->resolve($key);
    }

    public function resolve(string $key)
    {
        if (array_key_exists($key, $this->instances))
        {
            return $this->instances[$key];
        }
        return $this->container->resolve($key);
    }

    protected function bootServices()
    {
        foreach ($this->services as $service)
        {
            $this->resolve($service)->boot();
        }
    }

    public function boot()
    {
        $this->bootServices();
        spl_autoload_unregister([$this, 'autoload']);
    }

    public static function getInstance($id=0)
    {
        return static::$apps[$id];
    }
}
