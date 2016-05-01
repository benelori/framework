<?php

namespace Simplex\Service;


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class RouteCollector {

  protected $routeFile;
  protected $routeDefinitions;
  protected $routes;

  public function __construct() {
    $this->routes = new RouteCollection();
  }

  public function collectRoutes() {
    $this->routeFile = file_get_contents(__DIR__. '/../simplex.routes.yml');

    $yaml = new Parser();
    try {
      $this->routeDefinitions = $yaml->parse($this->routeFile);
      $this->compileRoutes();
    } catch (ParseException $e) {
      printf("Unable to parse the YAML string: %s", $e->getMessage());
    }

    return $this->routes;
  }

  public function compileRoutes() {
    try {
      foreach ($this->routeDefinitions as $name => $definition) {
        $routeOptions = [];
        foreach ($definition['defaults'] as $key => $default) {
          $routeOptions[$key] = $default;
        }
        $route = new Route($definition['path'], $routeOptions);
        $this->routes->add($name, $route);
      }
    }
    catch (\Exception $e) {
      printf("RouteCollection error: %s", $e->getMessage());
    }
  }

}
