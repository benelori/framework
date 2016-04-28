<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;

function render_template(Request $request)
{
  extract($request->attributes->all(), EXTR_SKIP);
  ob_start();
  include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

  return new Response(ob_get_clean());
}

$request = Request::createFromGlobals();

// The container has classes and their dependencies registered in it.
$sc = include __DIR__.'/../src/container.php';
// The routes for the matcher service are defined dynamically, so we can set
// its value from here.
$sc->setParameter('routes', include __DIR__.'/../src/app.php');

// The charset parameter of the listener.response service is dynamic, so we can
// set its value from here.
$sc->setParameter('charset', 'UTF-8');

// Registers custom Response Listener.
$sc->register('listener.string_response', 'Simplex\StringResponseListener');
$sc->getDefinition('dispatcher')
  ->addMethodCall('addSubscriber', array(new Reference('listener.string_response')))
;

$response = $sc->get('framework')->handle($request);

$response->send();
