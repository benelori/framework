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

$sc = include __DIR__.'/../src/container.php';
$sc->setParameter('routes', include __DIR__.'/../src/app.php');
$sc->register('listener.string_response', 'Simplex\StringResponseListener');
$sc->getDefinition('dispatcher')
  ->addMethodCall('addSubscriber', array(new Reference('listener.string_response')))
;
$sc->setParameter('charset', 'UTF-8');

$response = $sc->get('framework')->handle($request);

$response->send();
