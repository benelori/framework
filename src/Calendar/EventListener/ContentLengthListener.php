<?php

/**
 * @file
 * Contains Simplex\ContentLengthListener.
 */

namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ContentLengthListener implements EventSubscriberInterface{

  public function onResponse(GetResponseEvent $event)
  {
    var_dump($event);
    $response = $event->getResponse();
    $headers = $response->headers;

    if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
      $headers->set('Content-Length', strlen($response->getContent()));
    }
  }

  public static function getSubscribedEvents()
  {
    return array('response' => array('onResponse', -255));
  }

}
