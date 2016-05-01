<?php

require_once __DIR__.'/../vendor/autoload.php';

use Simplex\Framework;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
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
$kernel = new Framework($request);
$response = $kernel->handle();

$response->send();
