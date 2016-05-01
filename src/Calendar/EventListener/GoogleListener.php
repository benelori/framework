<?php

/**
 * @file
 * Contains Simplex\GoogleListener.
 */

namespace Simplex\Calendar\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class GoogleListener implements EventSubscriberInterface {

  public function onResponse(FilterResponseEvent $event)
  {
    $response = $event->getResponse();

    if ($response->isRedirection()
      || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
      || 'html' !== $event->getRequest()->getRequestFormat()
    ) {
      return;
    }

    $response->setContent($response->getContent().'GA CODE');
  }

  public static function getSubscribedEvents()
  {
    return array(KernelEvents::RESPONSE => 'onResponse');
  }

}
