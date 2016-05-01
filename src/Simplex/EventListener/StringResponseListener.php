<?php
/**
 * Created by PhpStorm.
 * User: lolo
 * Date: 30.04.2016
 * Time: 18:57
 */

namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;

class StringResponseListener implements EventSubscriberInterface
{
  public function onView(GetResponseForControllerResultEvent $event)
  {
    $response = $event->getControllerResult();

    if (is_string($response)) {
      $event->setResponse(new Response($response));
    }
  }

  public static function getSubscribedEvents()
  {
    return array('kernel.view' => 'onView');
  }
}
