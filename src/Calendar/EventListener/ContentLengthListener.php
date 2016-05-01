<?php

/**
 * @file
 * Contains Simplex\ContentLengthListener.
 */

namespace Simplex\Calendar\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ContentLengthListener implements EventSubscriberInterface{

  public function onResponse(FilterResponseEvent $event)
  {
    $response = $event->getResponse();
    $headers = $response->headers;

    if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
      $headers->set('Content-Length', strlen($response->getContent()));
    }
  }

  public static function getSubscribedEvents()
  {
    return array(KernelEvents::RESPONSE => array('onResponse', -255));
  }

}
