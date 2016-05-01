<?php

/**
 * @file
 * Contains Simplex\Framework.
 */

namespace Simplex;

use Simplex\Service\RouteCollector;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\HttpKernel;

class Framework {

  public $request;

  /**
   * @var ServiceCollector
   */
  protected $serviceCollector;

  /**
   * @var ContainerBuilder
   */
  protected $serviceContainerBuilder;

  /**
   * @var ServiceCompiler
   */
  protected $serviceCompiler;

  /**
   * @var RouteCollector
   */
  protected $routeCollector;


  public function __construct(Request $request) {
    $this->request = $request;
    $this->init();
  }

  public function init() {
    $this->serviceCollector = new ServiceCollector();
    $this->serviceContainerBuilder = new ContainerBuilder();
    $this->serviceCompiler = new ServiceCompiler($this->serviceContainerBuilder);
    $this->routeCollector = new RouteCollector();
  }

  public function handle() {
    try {
      $this->serviceCollector->getServiceYamls();
      $services = $this->serviceCollector->parseServiceFiles();
      $this->serviceCompiler->compile($services);

      $this->serviceContainerBuilder->register('listener.string_response', 'Simplex\StringResponseListener');
      $this->serviceContainerBuilder->getDefinition('dispatcher')
        ->addMethodCall('addSubscriber', array(new Reference('listener.string_response')))
      ;

      $routes = $this->routeCollector->collectRoutes();
      // TODO check why %% syntax is deprecated. And build RouteCollector.
      $this->serviceContainerBuilder->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
        ->setArguments(array('%routes%', new Reference('context')))
      ;

      $this->serviceContainerBuilder->setParameter('routes', $routes);

      $response = $this->serviceContainerBuilder->get('http_kernel')->handle($this->request);
    }
    catch (\Exception $e) {
      $response = new Response($e->getMessage(), 500);
    }

    return $response;
  }

}
