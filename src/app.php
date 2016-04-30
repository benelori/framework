<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routeDefinitions = [
  'hello' => [
    'path' => '/hello/{name}',
    'defaults' => [
      'name' => 'World',
      '_controller' => 'render_template',
    ],
  ],
  'bye' => [
    'path' => '/bye',
    'defaults' => [
      '_controller' => 'render_template',
    ],
  ],
  'leap_year' => [
    'path' => '/is_leap_year/{year}',
    'defaults' => [
      'year' => null,
      '_controller' => 'Calendar\\Controller\\LeapYearController::indexAction',
    ],
  ],
];

foreach ($routeDefinitions as $name => $definition) {
  $routeOptions = [];
  foreach ($definition['defaults'] as $key => $default) {
    $routeOptions[$key] = $default;
  }
  $route = new Route($definition['path'], $routeOptions);
  $routes->add($name, $route);
}

return $routes;
