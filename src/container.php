<?php

use Simplex\ServiceCompiler;
use Symfony\Component\DependencyInjection;

// TODO implement ServiceCollector.
$sc = new DependencyInjection\ContainerBuilder();

$services = [
  'services' => [
    'context' => [
      'class' => 'Symfony\Component\Routing\RequestContext',
    ],
    'request_stack' => [
      'class' => 'Symfony\Component\HttpFoundation\RequestStack',
    ],
    'resolver' => [
      'class' => 'Symfony\Component\HttpKernel\Controller\ControllerResolver',
    ],
    'listener.router' => [
      'class' => 'Symfony\Component\HttpKernel\EventListener\RouterListener',
      'arguments' => ["@matcher", "@request_stack"],
    ],
    'listener.response' => [
      'class' => 'Symfony\Component\HttpKernel\EventListener\ResponseListener',
      'arguments' => ["UTF-8"],
    ],
    'listener.exception' => [
      'class' => 'Symfony\Component\HttpKernel\EventListener\ExceptionListener',
      'arguments' => ['Calendar\\Controller\\ErrorController::exceptionAction'],
    ],
    'dispatcher' => [
      'class' => 'Symfony\Component\EventDispatcher\EventDispatcher',
      'calls' => [
        [
          'method' => 'addSubscriber',
          'arguments' => ["@listener.router"],
        ],
        [
          'method' => 'addSubscriber',
          'arguments' => ["@listener.response"],
        ],
        [
          'method' => 'addSubscriber',
          'arguments' => ["@listener.exception"],
        ],
      ],
    ],
    'framework' => [
      'class' => 'Simplex\Framework',
      'arguments' => ["@dispatcher", "@resolver"],
    ],
  ],
];

$container = new ServiceCompiler($sc);
$container->compile($services);

return $sc;
