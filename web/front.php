<?php

require_once __DIR__.'/../vendor/autoload.php';

use Simplex\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

function render_template(Request $request)
{
  extract($request->attributes->all(), EXTR_SKIP);
  ob_start();
  include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

  return new Response(ob_get_clean());
}

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);
$resolver = new ControllerResolver();

$dispatcher = new EventDispatcher();

$dispatcher->addSubscriber(new Simplex\ContentLengthListener());
$dispatcher->addSubscriber(new Simplex\GoogleListener());

// RouterListener is an implementation of the same logic we had in our framework
// it matches the incoming request and populates the request attributes with
// route parameters.
$dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, new RequestStack()));

$listener = new HttpKernel\EventListener\ExceptionListener(
  'Calendar\\Controller\\ErrorController::exceptionAction'
);
$dispatcher->addSubscriber($listener);
$dispatcher->addSubscriber(new HttpKernel\EventListener\ResponseListener('UTF-8'));
$dispatcher->addSubscriber(new HttpKernel\EventListener\StreamedResponseListener());

$framework = new Framework($dispatcher, $resolver);
$framework = new HttpKernel\HttpCache\HttpCache(
  $framework,
  new HttpKernel\HttpCache\Store(__DIR__.'/../cache')
);
$response = $framework->handle($request);

$response->send();
