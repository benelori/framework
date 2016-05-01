<?php

namespace Calendar\Controller;

use Calendar\Service\LeapYearManager;
use Simplex\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController extends ControllerBase {

  /**
   * @var \Calendar\Service\LeapYearManager
   */
  protected $leapYearManager;

  public function __construct(LeapYearManager $leapYearManager) {
    $this->leapYearManager = $leapYearManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('calendar.leap_year_manager')
    );
  }
  
  public function indexAction($year)
  {
    if ($this->leapYearManager->isLeapYear($year)) {
      return new Response('Yep, this is a leap year!');
    }

    return new Response('Nope, this is not a leap year.');
  }
  
}
