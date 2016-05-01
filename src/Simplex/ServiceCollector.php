<?php

namespace Simplex;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class ServiceCollector {

  protected $serviceFile;
  protected $routes;

  public function getServiceYamls() {
    $this->serviceFile = file_get_contents(__DIR__ . '/simplex.services.yml');
  }

  public function parseServiceFiles() {
    $yaml = new Parser();
    try {
      $this->routes = $yaml->parse($this->serviceFile);
    } catch (ParseException $e) {
      printf("Unable to parse the YAML string: %s", $e->getMessage());
    }

    return $this->routes;
  }

}
