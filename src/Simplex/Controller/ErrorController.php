<?php
/**
 * Created by PhpStorm.
 * User: lolo
 * Date: 30.04.2016
 * Time: 18:55
 */

namespace Calendar\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
  public function exceptionAction(FlattenException $exception)
  {
    $msg = 'Something went wrong! ('.$exception->getMessage().')';

    return new Response($msg, $exception->getStatusCode());
  }
}
