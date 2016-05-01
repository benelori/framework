<?php

namespace Simplex;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class ServiceCollector {

  protected $serviceFile;
  protected $services;
  protected $serviceFiles;

  public function getServiceYamls() {
    $this->serviceFile = file_get_contents(__DIR__ . '/simplex.services.yml');
  }

  public function parseServiceFiles() {
    $this->locateFiles();
    $this->services = ['services' => []];
    foreach ($this->serviceFiles as $filePath) {
      $serviceFile = file_get_contents($filePath);
      $yaml = new Parser();
      try {
        $parsedResult = $yaml->parse($serviceFile);
        $this->services['services'] += $parsedResult['services'];

      } catch (ParseException $e) {
        printf("Unable to parse the YAML string: %s", $e->getMessage());
      }
    }

    return $this->services;
  }

  public function locateFiles() {
    $finder = new Finder();
    $rootDirectory = getcwd();

    $directories = array(
      $rootDirectory . '/core',
      $rootDirectory . '/src',
    );

    $finder->files()->in($directories)->name('*.services.yml');
    foreach ($finder as $file) {
      $this->serviceFiles[] = $file->getRealpath();
    }
  }

}
