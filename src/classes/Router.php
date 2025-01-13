<?php

class Router {
    private $routes = [];

    public function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function handleRequest($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($this->buildRegexPattern($route['path']), $uri, $matches)) {
                array_shift($matches); // remove the first element (full matched path)
                $params = $matches; // pass the captured URL parameters as arguments

                return $this->invokeCallback($route['callback'], $params);
            }
        }
        // no route
        http_response_code(404);
        echo 'Not Found';
    }

    private function invokeCallback($callback, $params) {
        if (is_callable($callback)) {
            $reflection = new ReflectionFunction($callback);
            $arguments = [];

            foreach ($reflection->getParameters() as $parameter) {
                if (array_key_exists($parameter->getName(), $params)) {
                    $arguments[] = $params[$parameter->getName()];
                } else {
                    $arguments[] = null; // Use null if the parameter is not found in the captured params
                }
            }
            return $reflection->invokeArgs($arguments);
        }
        throw new InvalidArgumentException('Invalid callback specified.');
    }

    private function buildRegexPattern($path)
    {
        $pattern = preg_replace('/\//', '\\/', $path);
        $pattern = preg_replace('/\{([A-Za-z0-9_]+)\}/', '(?P<$1>[^\/]+(\.[^\/]+|))', $pattern);
        $pattern = '/^' . $pattern . '$/';
    
        return $pattern;
    }
    
    

}

// debug
/*
$router = new Router();

$router->addRoute('GET', '/users', function () {
    echo 'Get all users';
});

$router->addRoute('GET', '/users/{id}', function ($id) {
    echo 'Get user with ID: ' . $id;
});

$router->addRoute('GET', '/users/{id}/pass/{pass}', function ($id, $pass) {
    echo 'Get user with ID: ' . $id;
    echo 'Pass: ' . $pass;
});

$router->addRoute('POST', '/users', function () {
    echo 'Create a new user';
});

$router->addRoute('PUT', '/users/{id}', function ($id) {
    echo 'Update user with ID: ' . $id;
});

$router->addRoute('DELETE', '/users/{id}', function ($id) {
    echo 'Delete user with ID: ' . $id;
});

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$router->handleRequest($requestMethod, $requestUri);
*/