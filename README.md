## Nucleus

PHP Micro Framework written using service provider architecture.

## Features
- Environment Files
- Configuration Files
- Service Containers
- Supports Multiple App Instances
- Configurable Folder Structure


## Installation
Place the `Nucleus` folder anywhere in your project. Then create a `index.php` file
which creates a new app.

```php
require_once NUCLEUS . "/bootstrap.php";

$app = new Nucleus();

$app->boot();
```

## Extending Behavior

Next you can register custom packages by passing a class which extends the base ServiceProvider class. This is where you instantiate new classes and require dependencies.

```php
class RouteServiceProvider extends ServiceProvider
{

    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function register()
    {
        // called immediately after instantiation 
    }

    public function boot()
    {
        // called after all packages are registered
    }
}
```

### Create factories without boilerplate
The core of the app is based on a container which can store a recipe for creating a new instance of a class. This allows you to make a new instance of your class in the future without all the work. For example if your database class depends on a config file. You could do something like the following.

```php
class DatabaseServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(Mysql::class, function () {
            $config = config('db.mysql');
            return new MySqlConnector($config);
        });
    }

    //...
}
```

Then anywhere else in your application you could create a new MySqlConnector by resolving the string.
```php
$db = resolve(MySql::class)
$db->query(...)
```

If you do not want to create a new instance every time the class is resolved, then register it as a singleton by changing `bind` to `singleton`.

```php
class DatabaseServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(Mysql::class, function () {
            $config = config('db.mysql');
            return new MySqlConnector($config);
        });
    }

    //...
}
```

## Registering a new provider
You must register your service provider if you want the app to have access to the new behavior. You can manually register the class using the `register` method. 

```php
$app = new Nucleus();

$app->register(RouteServiceProvider::class);
$app->register(MysqlServiceProvider::class);

$app->boot();
```

Or if you are using the `ConfigServiceProvider` you can define a list of providers in your `app/config/app.php` file by default. 

```php
// app/config/app.php
<?php

return [

    "providers" => [
        \App\Providers\RouteServiceProvider::class,
        \App\Providers\MysqlServiceProvider::class,
    ],

];
```


## Routing
Routing is the entry point into your application. Each route registers a path and a closure. When the URI path and method match the route, the code will run.

```php
$route->get('/', function () {
    echo "Hello Nucleus";
    echo "<br>";
    echo "APP_ENV: " .  config('app.env');
});
```

### Route Groups
Routes can be prefixed with a route by configuring the router in the `RouteServiceProvider` to load routes under a prefix. This is useful if you have an API but you don't want to specify the prefix for every route.

```php
$this->router()->load_routes(
    routes: app_path('routes/api.php'),
    prefix: '/api'
);
```

### Model Binding
Routes are invoked using `Introspection::invoke()` which autowires the parameters using reflection. If the reflected type is a Model class, it will try to find a model where the column `Model::getRouteAccessor` matches the route parameter. More simply put, you can magically query the database by type-hinting your route function.

```php
// dumb route
$route->get('/users/{user_id}', function ($user_id) {
    // $user_id returns anything before the next slash;
    
    $user = User::where('id', $user_id)->first();

    // must check user for null;
    if (!isset($user)) abort_raw(404, 'model not found');

    //...
});

// magic route binding
$route->get('/users/{user}', function (User $user) {
    // $user returns pre-populated model if it exists, otherwise it fails with 404;

    // guarantees user is a model; user will never be null;
    echo "{$user->first_name}"
});
```

## Todo
- [ ] Database drivers
- [ ] View Rendering
- 